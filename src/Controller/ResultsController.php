<?php
namespace app\Controller;

use /** @noinspection PhpUnusedAliasInspection */
    App\Controller\AppController;
use App\Model\Table\ResultsTable;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Network\Response;
use Cake\ORM\TableRegistry;
use Exception;

/**
 * Results Controller
 *
 * @property ResultsTable Results
 */
class ResultsController extends AppController
{

    public $helpers = ['ReportFormater'];

    /**
     * Set global result for all pupils that takes the evaluation (for a single item of an evaluation)
     *
     * @param string $evaluationId the evaluation id related to the results
     * @param string $competenceId the item related to the results
     * @param string $result the result (could be A, B, C, D, NE, ABS)
     *
     * @return Response a HTTP 201 JSON formatted response
     * @access public
     */
    public function setresultforspecificitem($evaluationId = null, $competenceId = null, $result = null)
    {
        $this->gatekeeper(compact('evaluation_id', 'competence_id', 'result'), true);
        $this->Results->saveGlobalResultEvaluationItem($evaluationId, $competenceId, $result);

        return $this->sendOKresponseJSON();
    }

    /**
     * Set global result for all pupils of the same level (for a single item of an evaluation)
     *
     * @param string $evaluationId the evaluation id related to the results
     * @param string $competenceId the item related to the results
     * @param string $levelId the level related to the results
     * @param string $result the result (could be A, B, C, D, NE, ABS)
     *
     * @return Response a HTTP 201 JSON formatted response
     * @access public
     */
    public function setresultforspecificitemlevel($evaluationId = null, $competenceId = null, $levelId = null, $result = null)
    {
        $this->gatekeeper(compact('evaluation_id', 'competence_id', 'level_id', 'result'), true);
        $this->Results->saveGlobalResultEvaluationItemLevel($evaluationId, $competenceId, $levelId, $result);

        return $this->sendOKresponseJSON();
    }

    /**
     * Set result for a particular pupil (for just a single item of an evaluation)
     *
     * @param string $evaluationId the evaluation id related to the result
     * @param string $competenceId the item related to the result
     * @param string $pupilId the pupil id related to the result
     * @param string $result the result (could be A, B, C, D, NE, ABS)
     *
     * @return Response a HTTP 201 JSON formatted response
     * @access public
     */
    public function setresultforspecificitempupil($evaluationId = null, $competenceId = null, $pupilId = null, $result = null)
    {
        $this->gatekeeper(compact('evaluation_id', 'competence_id', 'pupil_id', 'result'), true);
        $this->Results->saveResultEvaluationItemPupil($evaluationId, $competenceId, $pupilId, $result);

        return $this->sendOKresponseJSON();
    }

    /**
     * Set global result for a particular pupil (for all competences of an evaluation)
     *
     * @param string $evaluationId the evaluation id related to the results
     * @param string $pupilId the pupil id related to the results
     * @param string $result the result (could be A, B, C, D, NE, ABS)
     *
     * @return Response a HTTP 201 JSON formatted response
     * @access public
     */
    public function setresultforspecificpupil($evaluationId = null, $pupilId = null, $result = null)
    {
        $this->gatekeeper(compact('evaluation_id', 'pupil_id', 'result'), true);
        $this->Results->saveGlobalResultEvaluationPupil($evaluationId, $pupilId, $result);

        return $this->sendOKresponseJSON();
    }

    /**
     * Utility function used to send an OK JSON response
     *
     * @param string $message custom message to display in JSON
     *                        message key of the response.
     *
     * @return Response a HTTP 201 JSON formatted response
     * @access private
     */
    private function sendOKresponseJSON($message = 'results created')
    {
        $this->response->type('application/json');
        $this->response->statusCode(201);
        $this->response->body(json_encode(['error' => false, 'message' => $message], JSON_PRETTY_PRINT));

        return $this->response;
    }

    /**
     * Check that current user has permission to perform action
     * and that passed parameters are valid/related records exists.
     *
     * @param array $parameters an array containing all parameters
     *                          that must be checked/validated.
     * @param bool $isXHR whether or not the action is performed through XHR
     *
     * @throws NotFoundException specified releted record has not been found
     * @throws UnauthorizedException user does not have permission to perform the action
     * @throws BadRequestException passed parameter value is incorrect
     *
     * @return void
     * @access private
     */
    private function gatekeeper(array $parameters, $isXHR = false)
    {
        if ($isXHR) {
            $this->viewBuilder()->layout('ajax');
        }

        try {
            if (!$this->Results->Evaluations->exists($parameters['evaluation_id'])) {
                throw new NotFoundException('Impossible de trouver cette évaluation !');
            }
            if (!in_array($parameters['result'], ['A', 'B', 'C', 'D', 'NE', 'ABS'])) {
                throw new BadRequestException('Le résultat renseigné est invalide.');
            }

            //We have a competence_id so we also check that item belongs to evaluation
            if (array_key_exists('competence_id', $parameters)) {
                if (!$this->Results->Evaluations->itemBelongsToEvaluation($parameters['evaluation_id'], $parameters['competence_id'])) {
                    throw new NotFoundException('L\'item spécifié en paramètre n\'est pas associé à cette évaluation !');
                }
            }

            //We have a level_id so we also check that level belongs to classroom
            if (array_key_exists('level_id', $parameters)) {
                if (!$this->Results->Evaluations->levelBelongsToClassroom($parameters['evaluation_id'], $parameters['level_id'])) {
                    throw new NotFoundException('Aucun élève associé au niveau spécifié en paramètre pour cette classe !');
                }
            }

            //We have a pupil_id so we also check that pupil belongs to evaluation
            if (array_key_exists('pupil_id', $parameters)) {
                if (!$this->Results->Evaluations->pupilBelongsToEvaluation($parameters['evaluation_id'], $parameters['pupil_id'])) {
                    throw new NotFoundException('L\'élève spécifié en paramètre n\'a pas passé cette évaluation !');
                }
            }
        } catch (Exception $e) {
            $this->response = new Response;
            $this->response->type('application/json');
            $this->response->body(json_encode(['error' => true, 'message' => $e->getMessage()], JSON_PRETTY_PRINT));
            $this->response->send();
            exit();
        }
    }

    /**
     * @param null $evaluationId Corresponding evaluation_id
     * @return void
     */
    public function global($evaluationId = null)
    {
        $this->set('title_for_layout', 'Saisie souris des résultats');

        $evaluation = $this->Results->Evaluations->get($evaluationId);
        $levelsPupils = $this->Results->Evaluations->findPupilsByLevels($evaluation->id);
        $competences = $this->Results->Evaluations->findCompetencesByPosition($evaluation->id);
        $results = $this->Results->find('all', [
            'conditions' => [
                'evaluation_id' => $evaluationId
            ],
            'recursive' => -1
        ]);
        $jsonResults = [];
        foreach ($results as $num => $result) {
            $jsonResults[$num]['competence_id'] = $result->competence_id;
            $jsonResults[$num]['pupil_id'] = $result->pupil_id;
            $jsonResults[$num]['result'] = $result->result;
        }
        $jsonResults = json_encode($jsonResults);

        $this->set(compact('levelsPupils', 'competences', 'evaluation', 'jsonResults'));
    }

    /**
     * @param null $id Corresponding evaluation_id
     * @return void
     */
    public function evaluation($id = null)
    {
        $evaluation = $this->Results->Evaluations->get($id);
        $results = $this->Results->find()
            ->select(['competence_id', 'pupil_id', 'result'])
            ->where(['evaluation_id' => $evaluation->id]);
        $this->set(compact('results'));
        $this->set('_serialize', 'results');
    }

    /**
     * @param null $id Corresponding evaluation_id
     * @return void
     */
    public function add($id = null)
    {
        $evaluation = $this->Results->Evaluations->get($id);

        if ($this->request->is(['post'])) {
            //We first check that the pupil belongs to this test
            $pupilId = $this->request->data['Pupils']['id'];
            if (!$this->Results->Evaluations->pupilBelongsToEvaluation($evaluation->id, $pupilId)) {
                throw new NotFoundException('L\'élève spécifié en paramètre n\'a pas passé cette évaluation !');
            }

            //We retreive all competences that belongs to that test
            $competences = $this->Results->Evaluations->getCompetencesThatBelongsToEvaluation($evaluation->id);
            $allowedResults = ['A', 'B', 'C', 'D', 'NE', 'ABS'];

            //Next we're build our data array to pass to newEntities()
            $data = [];
            foreach ($this->request->data['Results'] as $result) {
                //If result is invalid or item does not belongs to this test, we skip the record
                if (in_array($result['result'], $allowedResults) || in_array($result['competence_id'], $competences)) {
                    $entity = [
                        'pupil_id' => $pupilId,
                        'evaluation_id' => $evaluation->id,
                        'competence_id' => $result['competence_id'],
                        'result' => $result['result']
                    ];
                    $entity = $this->setGrade($entity);
                    array_push($data, $entity);
                }
            }

            $results = $this->Results->newEntities($data);
            $resultTable = $this->Results;

            if (count($results)) {
                try {
                    $resultTable->connection()->transactional(function () use ($resultTable, $results) {
                        $resultTable->deleteAll(['pupil_id' => $results[0]->pupil_id, 'evaluation_id' => $results[0]->evaluation_id]);
                        foreach ($results as $entity) {
                            $resultTable->save($entity, ['atomic' => false]);
                        }
                    });

                    $message = 'Results saved';
                } catch (\Cake\Core\Exception\Exception $e) {
                    $message = $e->getMessage();
                }
            } else {
                //If we haven't any results, we clear current results
                $resultTable->deleteAll(['pupil_id' => $pupilId, 'evaluation_id' => $evaluation->id]);
                $message = 'Results cleared';
            }
            $this->set(compact('message'));
            $this->set('_serialize', 'message');
        } else {
            $hasCompetences = $this->Results->Evaluations->EvaluationsCompetences->find('all', [
                'conditions' => ['evaluation_id' => $evaluation->id],
                'recursive' => 0
            ])->count();
            if (!$hasCompetences) {
                $this->Flash->error('Impossible de saisir des résultats, aucun item associé à cette évaluation !');
                $this->redirect(['controller' => 'evaluations', 'action' => 'competences', $evaluation->id]);
            }

            $levelsPupils = $this->Results->Evaluations->findPupilsByLevels($evaluation->id);
            $competences = $this->Results->Evaluations->findCompetencesByPosition($evaluation->id);

            $this->set(compact('evaluation', 'levels_pupils', 'competences'));
        }
    }

    /**
     * @param string $result result value
     * @internal param int $iteration
     * @return array data array
     */
    public function setGrade($result)
    {
        switch ($result['result']) {
            case 'A':
                $result['grade_a'] = 1;
                break;
            case 'B':
                $result['grade_b'] = 1;
                break;
            case 'C':
                $result['grade_c'] = 1;
                break;
            case 'D':
                $result['grade_d'] = 1;
                break;
        }
        

        return $result;
    }

    public function analyseresults($id = null)
    {
        $this->Reports = TableRegistry::get('Reports');
        $report = $this->Reports->get($id);
        $this->set('report', $report);

        $results = $this->Results->find();
        $results = $this->Results->find('all', [
            'fields' => [
                'Pupils.name', 'Pupils.first_name',
                'sum_grade_a' => $results->func()->sum('grade_a'),
                'sum_grade_b' => $results->func()->sum('grade_b'),
                'sum_grade_c' => $results->func()->sum('grade_c'),
                'sum_grade_d' => $results->func()->sum('grade_d')
            ],
            'order' => ['Pupils.name', 'Pupils.first_name'],
            'group' => ['Pupils.id'],
            'conditions' => [
                'Evaluations.period_id IN' => explode(',', $report->period_id),
                'Evaluations.classroom_id' => $report->classroom_id
            ],
            'contain' => [
                'Evaluations',
                'Pupils'
            ]
        ]);

        $this->set('results', $results);
    }
}
