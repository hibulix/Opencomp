<?php
namespace app\Controller;

use App\Controller\AppController;
use App\Model\Table\EvaluationsTable;
use Cake\Event\Event;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Response;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

/**
 * Evaluations Controller
 *
 * @property EvaluationsTable $Evaluations
 */
class EvaluationsController extends AppController
{
    /**
     * Initialization hook method.
     *
     * Implement this method to avoid having to overwrite
     * the constructor and call parent.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Security');
    }

    /**
     * @param Event $event Cake event
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Security->config('unlockedActions', ['attachcompetence']);
    }

    /**
     * @param null $id classroom_id
     * @return void
     */
    public function pupils($id = null)
    {
        $evaluation = $this->Evaluations->get($id, [
            'contain' => [
                'Users', 'Periods', 'Classrooms'
            ],
            'conditions' => ['unrated' => 0]
        ]);
        $this->set('title_for_layout', $evaluation->title);
        $levelsPupils = $this->Evaluations->findPupilsByLevels($id);
        $this->set(compact('evaluation', 'levelsPupils'));
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id evaluation_id
     * @return void
     */
    public function competences($id = null)
    {
        $this->set('title_for_layout', __('Détails d\'une évaluation'));

        $evaluation = $this->Evaluations->get($id, [
            'contain' => [
                'Classrooms.Establishments',
                'Competences' => function (Query $q) {
                    return $q
                        ->order(['EvaluationsCompetences.position' => 'ASC']);
                },
            ],
            'conditions' => ['unrated' => 0]
        ]);
        $this->set(compact('evaluation'));
    }

    /**
     * @param null $id evaluation_id
     * @return Response|void
     */
    public function attachcompetence($id = null)
    {
        if ($this->request->is('post')) {
            foreach ($this->request->data('competenceIds') as $competenceId) {
                if (!$this->Evaluations->EvaluationsCompetences->isCompetenceAlreadyAttachedToEvaluation($id, $competenceId)) {
                    $data = $this->Evaluations->EvaluationsCompetences->newEntity();
                    $data->evaluation_id = $id;
                    $data->competence_id = $competenceId;
                    $data->position = $this->Evaluations->EvaluationsCompetences->getNextCompentencePosition($id);

                    $this->Evaluations->EvaluationsCompetences->save($data);
                }
            }
        } else {
            $evaluation = $this->Evaluations->get($id);
            $repository = $this->Evaluations->getRepository($id);

            $list = $this->Evaluations->EvaluationsCompetences->Competences
                ->find('all')
                ->select(['id', 'parent_id', 'title', 'type', 'end_cycle'])
                ->where(['repository_id' => $repository])->toArray();

            $listjson = array_map(function ($list) {
                if ($list['parent_id'] === null) {
                    $list['parent_id'] = '#';
                }
                if ($list['end_cycle'] == 1) {
                    $list['type'] = 'cycle';
                }

                return [
                    'id' => strval($list['id']),
                    'parent' => strval($list['parent_id']),
                    'text' => $list['title'],
                    'type' => strval($list['type']),
                    'data' => [ 'type' => strval($list['type']) ]
                ];
            }, $list);

            $this->set(compact('evaluation'));
            $this->set('listjson', json_encode($listjson));
        }
    }

    /**
     * @param null $id Evaluation id
     * @param null $parentCompetenceId Parent competence id
     * @return void
     */
    public function addcompetence($id = null, $parentCompetenceId = null)
    {
        $competence = $this->Evaluations->EvaluationsCompetences->Competences->newEntity();
        $evaluation = $this->Evaluations->get($id);
        $repositoryId = $this->Evaluations->getRepository($id);

        if ($this->request->is('post')) {
            $competence = $this->Evaluations->EvaluationsCompetences->Competences->patchEntity($competence, $this->request->data);
            if ($this->Evaluations->EvaluationsCompetences->Competences->save($competence)) {
                $evaluationCompetence = $this->Evaluations->EvaluationsCompetences->newEntity();
                $evaluationCompetence->evaluation_id = $id;
                $evaluationCompetence->competence_id = $competence->id;
                $evaluationCompetence->position = $this->Evaluations->EvaluationsCompetences->getNextCompentencePosition($id);
                if ($this->Evaluations->EvaluationsCompetences->save($evaluationCompetence)) {
                    $this->Flash->success(__('Votre compétence a été créée et associée à l\'évaluation.'));
                    $this->redirect(['action' => 'competences', $id]);
                }
            }
        }

        $this->set(compact('competence', 'parentCompetenceId', 'repositoryId', 'evaluation'));
    }

    /**
     * @param null $id evaluation_id
     * @return void
     */
    public function results($id = null)
    {
        $evaluation = $this->Evaluations->get($id, [
            'contain' => [
                'Classrooms'
            ],
            'conditions' => ['unrated' => 0]
        ]);
        $levelsPupils = $this->Evaluations->findPupilsByLevels($evaluation->id);
        $competences = $this->Evaluations->findCompetencesByPosition($evaluation->id);

        $this->set(compact('evaluation', 'levelsPupils', 'competences'));
    }

    /**
     * @param null $id evaluation_id
     * @return void
     */
    public function insights($id = null)
    {
        $evaluation = $this->Evaluations->get($id, [
            'contain' => [
                'Classrooms',
                'Competences' => [
                    'sort' => ['EvaluationsCompetences.position' => 'ASC']
                ],
                'Results'
            ],
            'conditions' => ['unrated' => 0]
        ]);
        $this->set('title_for_layout', $evaluation->title);

        $competencex = '';
        foreach ($evaluation->competences as $competence) {
            $competenceno = $competence->_joinData['position'];
            $competencex .= "'Compétence $competenceno', ";
        }

        $competenceDivision = $this->Evaluations->Results->findItemDivision($id);
        $globalResults = $this->Evaluations->Results->globalResults($id);

        $this->set(compact('evaluation', 'globalResults'));
        $this->set('x', substr($competencex, 0, -2));
        $this->set('y', $competenceDivision);
    }

    /**
     * add method
     *
     * @param null $id classroom_id
     * @return void
     */
    public function add($id = null)
    {
        $this->set('title_for_layout', __('Ajouter une évaluation'));

        $classroom = $this->Evaluations->Classrooms->get($id, ['contain' => 'Establishments']);
        $evaluation = $this->Evaluations->newEntity();

        $users = $this->Evaluations->Classrooms->Users
            ->find('list')->matching('Classrooms', function (Query $q) use ($id) {
                return $q->where(['Classrooms.id' => $id]);
            });

        $pupils = '""';

        if ($this->request->is('post')) {
            $evaluation = $this->Evaluations->newEntity($this->request->data);

            $evaluation->classroom_id = $classroom->id;
            if ($this->Evaluations->save($evaluation)) {
                $evaluationUserTable = TableRegistry::get('EvaluationsUsers');
                $evaluationUser = $evaluationUserTable->newEntity([
                    'evaluation_id' => $evaluation->id,
                    'user_id' => $this->Auth->user('id'),
                    'ownership' => 'OWNER'
                ]);
                $evaluationUserTable->save($evaluationUser);
                $this->Flash->success('La nouvelle évaluation a été correctement ajoutée.');
                $this->redirect(['controller' => 'evaluations', 'action' => 'competences', $evaluation->id]);
            } else {
                $this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
                $pupils = json_encode($this->request->data['pupils']['_ids']);
            }
        }

        $periods = $this->Evaluations->Periods->find('list', [
            'conditions' => [
                'classroom_id' => $classroom->id
            ]
        ]);

        $this->set(compact('evaluation', 'classroom', 'users', 'periods', 'pupils', 'current_period'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id evaluation_id
     * @return void
     */
    public function edit($id = null)
    {
        $this->set('title_for_layout', __('Modifier une évaluation'));

        $evaluation = $this->Evaluations->get($id, ['contain' => ['Pupils', 'Classrooms']]);
        $classroom = $this->Evaluations->Classrooms->get($evaluation->classroom_id, ['contain' => 'Establishments']);

        $queryPupils = $this->Evaluations->EvaluationsPupils->find('list', [
            'keyField' => 'id',
            'valueField' => 'pupil_id'
        ])->where(['evaluation_id' => $evaluation->id])->toArray();
        $pupils = json_encode(array_values($queryPupils));

        if ($this->request->is(['patch', 'post', 'put'])) {
            $evaluation = $this->Evaluations->patchEntity($evaluation, $this->request->data);

            if ($this->Evaluations->save($evaluation)) {
                $this->Flash->success('L\'évaluation a été correctement modifiée.');
                $this->redirect(['controller' => 'evaluations', 'action' => 'competences', $evaluation->id]);
            } else {
                $this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
                $pupils = json_encode($this->request->data['pupils']['_ids']);
            }
        }

        $periods = $this->Evaluations->Periods->find('list', [
            'conditions' => [
                'classroom_id' => $evaluation->classroom->id
            ]
        ]);

        $this->set(compact('evaluation', 'users', 'periods', 'classroom', 'pupils', 'current_period'));
    }

    /**
     * delete method
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param string $id evaluation_id
     * @return void
     */
    public function delete($id = null)
    {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Evaluation->id = $id;
        $classroomId = $this->Evaluation->read('Evaluation.classroom_id', $id);
        if ($this->Evaluation->delete()) {
            $this->Flash->success('L\'évaluation a été correctement supprimée');
            $this->redirect([
                'controller' => 'classrooms',
                'action' => 'viewtests',
                $classroomId['Evaluation']['classroom_id']]);
        }
        $this->Flash->error('L\'évaluation n\'a pas pu être supprimée en raison d\'une erreur interne');
        $this->redirect([
            'controller' => 'classrooms',
            'action' => 'viewtests']);
    }
}
