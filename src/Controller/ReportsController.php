<?php

namespace app\Controller;

use /** @noinspection PhpUnusedAliasInspection */
    App\Controller\AppController;
use App\Model\Table\ReportsTable;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Text;
use Pheanstalk\Exception;
use Pheanstalk\Pheanstalk;

/**
 * @property ReportsTable Reports
 */
class ReportsController extends AppController
{

    /**
     * edit method
     *
     * @throws NotFoundException
     * @return void
     */
    public function add()
    {
        $this->set('title_for_layout', __('Ajouter un bulletin'));

        $report = $this->Reports->newEntity();
        $classroom = $this->Reports->Classrooms->get($this->request->query['classroom_id']);

        if ($this->request->is('post')) {
            $report = $this->Reports->newEntity($this->request->data);
            $report->period_id = implode(',', $this->request->data['period_id']);
            $report->page_break = implode(',', $this->request->data['page_break']);
            $report->classroom_id = $classroom->id;
            if ($this->Reports->save($report)) {
                $this->Flash->success('Le bulletin a été correctement mis à jour.');
                $this->redirect(['controller' => 'classrooms', 'action' => 'viewreports', $classroom->id]);
            } else {
                $this->set('classroom_id', $classroom->id);
                $this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
            }
        }

        $this->set('classroom_id', $report->classroom_id);
        $this->set('report', $report);
        $this->getReportAssociatedInfos($classroom->id);
    }

    public function requestGeneration($id = null)
    {

        $report = $this->Reports->get($id);

        if ($report->beanstalkd_finished !== 1) {
            $this->redirect(['action' => 'generationProgress', $id]);
        }

        $this->Results = TableRegistry::get('Results');
        $ReqPupils = $this->Results->find('all', [
            'fields' => ['pupil_id'],
            'order' => ['name', 'first_name'],
            'conditions' => [
                'Evaluations.period_id IN' => explode(',', $report->period_id),
                'Evaluations.classroom_id' => $report->classroom_id
            ],
            'contain' => [
                'Pupils',
                'Evaluations.Periods',
                'Evaluations.Classrooms'
            ]
        ]);

        foreach ($ReqPupils as $pupils) {
            $pup[] = $pupils->pupil_id;
        }

        if (!isset($pup)) {
            $this->Flash->error('Aucun résultat saisi pour la/les période(s) configurée(s). Génération annulée !');
            $this->redirect(['controller' => 'classrooms', 'action' => 'viewreports', $report->classroom_id]);
        }

        $pup = array_values(array_unique($pup));

        $pheanstalk = new Pheanstalk(Configure::read('beanstalkd_host'));
        $pupilsJobs = [];

        foreach ($pup as $id) {
            $jobId = $pheanstalk
                ->useTube('generate-report')
                ->put(json_encode(['action' => 'generate', 'pupil_id' => $id, 'report_id' => $report->id]));
            $pupilsJobs[$id] = $jobId;
        }

        $jobId = $pheanstalk
            ->useTube('generate-report')
            ->put(json_encode(['action' => 'concatenate', 'report_id' => $report->id]));

        $pupilsJobs['concatenate'] = $jobId;

        $this->Reports->id = $report['Report']['id'];
        $report->beanstalkd_jobs = serialize($pupilsJobs);
        $report->beanstalkd_finished = 0;
        $this->Reports->save($report);

        $this->redirect(['controller' => 'reports', 'action' => 'generationProgress', $report['Report']['id']]);
    }

    public function generationProgress($id = null)
    {
        $report = $this->Reports->get($id);
        $classroom = $this->Reports->Classrooms->get($report->classroom_id, ['contain' => ['Establishments', 'User', 'Users', 'Years']]);
        $this->set('classroom', $classroom);
        $this->set('report', $report);
    }

    /**
     * @param null $id
     * @return array
     */
    public function generationProgressWidget($id = null)
    {
        $this->viewBuilder()->layout('ajax');

        $report = $this->Reports->get($id);
        $jobsIds = unserialize($report->beanstalkd_jobs);

        $this->Pupils = TableRegistry::get('Pupils');
        $pupils = $this->Pupils->find('list', [
            'conditions' => [
                'id IN' => array_keys($jobsIds)
            ]
        ])->toArray();

        $pupilsStatus = [];
        $pheanstalk = new Pheanstalk(Configure::read('beanstalkd_host'));

        $concatenateJobId = array_pop($jobsIds);
        foreach ($jobsIds as $pupilId => $jobId) {
            try {
                $pupilsStatus[$pupilId]['name'] = $pupils[$pupilId];
                $pupilsStatus[$pupilId]['state'] = $pheanstalk->statsJob($jobId)->state;
            } catch (Exception $e) {
                $pupilsStatus[$pupilId]['name'] = $pupils[$pupilId];
                $pupilsStatus[$pupilId]['state'] = 'done';
            }
        }

        try {
            $pupilsStatus[$pupilId]['name'] = "Fusion des bulletins";
            $pupilsStatus[$pupilId]['state'] = $pheanstalk->statsJob($concatenateJobId)->state;
        } catch (Exception $e) {
            $pupilsStatus[$pupilId]['name'] = "Fusion des bulletins";
            $pupilsStatus[$pupilId]['state'] = 'done';
        }

        if ($this->request->is('requested')) {
            return $pupilsStatus;
        }

        $this->set('pupilsStates', $pupilsStatus);
    }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function edit($id = null)
    {
        $this->set('title_for_layout', __('Modifier un bulletin'));
        
        $report = $this->Reports->get($id);
        $report->period_id = explode(',', $report->period_id);
        $report->page_break = explode(',', $report->page_break);
        $this->set('report', $report);

        if ($this->request->is(['post', 'patch', 'put'])) {
            $report = $this->Reports->patchEntity($report, $this->request->data);
            $report->period_id = implode(',', $this->request->data['period_id']);
            $report->page_break = implode(',', $this->request->data['page_break']);
            if ($this->Reports->save($report)) {
                $this->Flash->success('Le bulletin a été correctement mis à jour.');
                $this->redirect(['controller' => 'classrooms', 'action' => 'viewreports', $this->request->data['classroom_id']]);
            } else {
                $classroomId = $this->request->data['classroom_id'];
                $this->set('classroom_id', $classroomId);
                $this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
            }
        }

        $this->set('classroom_id', $report->classroom_id);
        $this->getReportAssociatedInfos($report->classroom_id);
    }

    /**
     * method to initialize data related to reports (for combobox for example)
     *
     * @param string $classroomId
     * @return void
     */
    private function getReportAssociatedInfos($classroomId)
    {
        $this->Competences = TableRegistry::get('Competences');
        $competences = $this->Competences->find('treeList');

        $this->Classrooms = TableRegistry::get('Classrooms');
        $classroom = $this->Classrooms->get($classroomId);

        $this->Settings = TableRegistry::get('Settings');
        $currentYear = $this->Settings->find('all', ['conditions' => ['Settings.key' => 'currentYear']])->first();

        $this->Periods = TableRegistry::get('Periods');
        $periods = $this->Periods->find('list', [
            'conditions' => ['establishment_id' => $classroom->establishment_id, 'year_id' => $currentYear->value]]);

        $this->set(compact('classrooms', 'users', 'periods', 'pupils', 'competences'));
    }

    public function download($id)
    {

        $report = $this->Reports->get($id);

        $this->response->file(
            APP . "files" . DS . "reports" . DS . $id.".pdf",
            [
                'download' => true,
                'name' => Text::slug($report->title).".pdf"
            ]
        );

        return $this->response;
    }

    /**
     * delete method
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null)
    {
        $report = $this->Reports->get($id);
        $this->request->allowMethod(['post', 'delete']);
        $classroomId = $report->classroom_id;
        if ($this->Reports->delete($report)) {
            $this->Flash->success('Le bulletin a été correctement supprimé');
        } else {
            $this->Flash->error('Le bulletin n\'a pas pu être supprimée en raison d\'une erreur interne');
        }

        $this->redirect([
            'controller'    => 'classrooms',
            'action'        => 'viewreports',
            $classroomId]);
    }
}
