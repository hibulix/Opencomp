<?php

use Pheanstalk\Pheanstalk;

class ReportsController extends AppController {

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function add() {
        $this->set('title_for_layout', __('Ajouter un bulletin'));

        if(isset($this->request->params['named']['classroom_id'])) {
            $classroom_id = intval($this->request->params['named']['classroom_id']);
            $this->set('classroom_id', $classroom_id);
            $this->Report->Classroom->id = $classroom_id;
            if (!$this->Report->Classroom->exists()) {
                throw new NotFoundException(__('The classroom_id provided does not exist !'));
            }
        } else {
            throw new NotFoundException(__('You must provide a classroom_id in order to add a test to this classroom !'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {

            if ($this->Report->save($this->request->data)) {
                $this->Session->setFlash(__('Le bulletin a été correctement ajouté.'), 'flash_success');
                $this->redirect(array('controller' => 'classrooms','action' => 'viewreports', $this->request->data['Report']['classroom_id']));
            } else {
                $classroom_id = $this->request->data['Report']['classroom_id'];
                $this->set('classroom_id', $classroom_id);
                $this->Session->setFlash(__('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.'), 'flash_error');
            }
        } else {
            $this->set('classroom_id', $classroom_id);
        }

        $this->getReportAssociatedInfos($classroom_id);
    }

    public function requestGeneration($id = null){
        $this->Report->id = $id;
        if (!$this->Report->exists()) {
            throw new NotFoundException(__('The report_id provided does not exist !'));
        }

        $report = $this->Report->find('first', array(
            'conditions' => array('Report.id' => $id)
        ));

        if(!$report['Report']['beanstalkd_finished'])
            $this->redirect(['action'=>'generationProgress',$id]);

        $this->loadModel('Result');
        $ReqPupils = $this->Result->find('all', array(
            'fields' => array('pupil_id'),
            'order' => array('name', 'first_name'),
            'conditions' => array(
                'Evaluation.period_id' => $report['Report']['period_id'],
                'Evaluation.classroom_id' => $report['Classroom']['id']
            ),
            'contain' => array(
                'Pupil.id',
                'Evaluation.Period.id',
                'Evaluation.Classroom.id'
            )
        ));

        foreach($ReqPupils as $pupils){
            $pup[] = $pupils['Pupil']['id'];
        }

        if(!isset($pup)){
            $this->Session->setFlash(__('Aucun résultat saisi pour la/les période(s) configurée(s). Génération annulée !'), 'flash_error');
            $this->redirect(array('controller' => 'classrooms', 'action' => 'viewreports', $report['Classroom']['id']));
        }

        $pup = array_values(array_unique($pup));

        $pheanstalk = new Pheanstalk('127.0.0.1');
        $pupilsJobs = [];

        foreach($pup as $id){
            $jobId = $pheanstalk
                ->useTube('generate-report')
                ->put(json_encode(['action'=>'generate', 'pupil_id'=>$id, 'report_id'=>$report['Report']['id']]));
            $pupilsJobs[$id] = $jobId;
        }

        $jobId = $pheanstalk
            ->useTube('generate-report')
            ->put(json_encode(['action'=>'concatenate', 'report_id'=>$report['Report']['id']]));

        $pupilsJobs['concatenate'] = $jobId;

        $this->Report->id = $report['Report']['id'];
        $this->Report->saveField('beanstalkd_jobs', serialize($pupilsJobs));
        $this->Report->saveField('beanstalkd_finished', 0);

        $this->redirect(array('controller' => 'reports', 'action' => 'generationProgress', $report['Report']['id']));
    }

    public function generationProgress($id = null){
        $report = $this->Report->findById($id);
        $classroom = $this->Report->Classroom->find('first', array(
            'conditions' => array('Classroom.id' => $report['Report']['classroom_id'])
        ));
        $this->set('classroom', $classroom);
        $this->set('report', $report);
    }

    public function generationProgressWidget($id = null){
        $this->layout = 'ajax';

        $this->Report->id = $id;
        if (!$this->Report->exists()) {
            throw new NotFoundException(__('Invalid report'));
        }

        $report = $this->Report->read('beanstalkd_jobs', $id);
        $jobsIds = unserialize($report['Report']['beanstalkd_jobs']);

        $this->loadModel('Pupil');
        $pupils = $this->Pupil->find('list',[
            'fields' => [
                'id', 'wellnamed'
            ],
            'conditions' => [
                'id IN' => array_keys($jobsIds)
            ],
            'recursive' => -1
        ]);

        $pupilsStatus = [];
        $pheanstalk = new Pheanstalk('127.0.0.1');

        $concatenateJobId = array_pop($jobsIds);
        foreach($jobsIds as $pupil_id => $job_id){
            try{
                $pupilsStatus[$pupil_id]['name'] = $pupils[$pupil_id];
                $pupilsStatus[$pupil_id]['state'] = $pheanstalk->statsJob($job_id)->state;
            }catch(Exception $e){
                $pupilsStatus[$pupil_id]['name'] = $pupils[$pupil_id];
                $pupilsStatus[$pupil_id]['state'] = 'done';
            }
        }

        try{
            $pupilsStatus[$pupil_id]['name'] = "Fusion des bulletins";
            $pupilsStatus[$pupil_id]['state'] = $pheanstalk->statsJob($concatenateJobId)->state;
        }catch(Exception $e){
            $pupilsStatus[$pupil_id]['name'] = "Fusion des bulletins";
            $pupilsStatus[$pupil_id]['state'] = 'done';
        }

        if ($this->request->is('requested')) {
            return $pupilsStatus;
        }

        $this->set('pupilsStates',$pupilsStatus);
    }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->set('title_for_layout', __('Modifier un bulletin'));
		
		$this->Report->id = $id;
		if (!$this->Report->exists()) {
			throw new NotFoundException(__('Invalid report'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			
			if ($this->Report->save($this->request->data)) {
				$this->Session->setFlash(__('Le bulletin a été correctement mis à jour.'), 'flash_success');
				$this->redirect(array('controller' => 'classrooms','action' => 'viewreports', $this->request->data['Report']['classroom_id']));
			} else {
				$classroom_id = $this->request->data['Report']['classroom_id'];
				$this->set('classroom_id', $classroom_id);
				$this->Session->setFlash(__('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.'), 'flash_error');
			}
		} else {
			$this->request->data = $this->Report->read(null, $id);
			
			$classroom_id = $this->request->data['Classroom']['id'];
			$this->set('report_id', $id);
			$this->set('classroom_id', $classroom_id);
		}
		
		$this->getReportAssociatedInfos($classroom_id);
	}

    /**
     * method to initialize data related to reports (for combobox for example)
     *
     * @param string $classroom_id
     * @return void
     */
    private function getReportAssociatedInfos($classroom_id) {
        $this->loadModel('Competence');
        $competences = $this->Competence->generateTreeList(null, null, null, '',-1);

        $this->loadModel('Classroom');
        $this->Classroom->recursive = 0;
        $this->Classroom->id = $classroom_id;
        $classroom = $this->Classroom->read();

        $this->loadModel('Setting');
        $currentYear = $this->Setting->find('first', array('conditions' => array('Setting.key' => 'currentYear')));

        $this->loadModel('Period');
        $periods = $this->Period->find('list', array(
            'conditions' => array('establishment_id' => $classroom['Classroom']['establishment_id'], 'year_id' => $currentYear['Setting']['value']),
            'recursive' => 0));

        $this->set(compact('classrooms', 'users', 'periods', 'pupils', 'competences'));
    }

    public function download($id) {
        $this->Report->id = $id;
        if (!$this->Report->exists()) {
            throw new NotFoundException(__('Invalid report'));
        }

        $report = $this->Report->findById($id);

        $this->response->file(APP . "files" . DS . "reports" . DS . $id.".pdf",
            [
                'download' => true,
                'name' => Inflector::slug($report['Report']['title']).".pdf"
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
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Report->id = $id;
        if (!$this->Report->exists()) {
            throw new NotFoundException(__('Invalid report'));
        }
        $classroom_id = $this->Report->read('Report.classroom_id', $id);
        if ($this->Report->delete()) {
            $this->Session->setFlash(__("Le bulletin a été correctement supprimé"), 'flash_success');
            $this->redirect(array(
                'controller'    => 'classrooms',
                'action'        => 'viewreports',
                $classroom_id['Report']['classroom_id']));
        }
        $this->Session->setFlash(__("Le bulletin n'a pas pu être supprimée en raison d'une erreur interne"), 'flash_error');
        $this->redirect(array(
            'controller'    => 'classrooms',
            'action'        => 'viewreports'));
    }
	
}
