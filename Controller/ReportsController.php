<?php
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