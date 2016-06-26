<?php
App::uses('AppController', 'Controller');
/**
 * Classrooms Controller
 *
 * @property Classroom $Classroom
 * @property LpcnodesPupil $LpcnodesPupil
 * @property Lpcnode $Lpcnode
 */
class ClassroomsController extends AppController {

    /**
     * Fonction permettant de déterminer les droits d'accès à une classe
     *
     * @param null $user
     * @return bool
     */
    public function isAuthorized($user = null) {
        if(isset($this->request['pass'][0])){
            //Vérification de l'existance de la classe
            $this->Classroom->id = $this->request['pass'][0];
            if (!$this->Classroom->exists()) {
                return false;
            //L'administrateur a toujours accès
            }elseif($user['role'] === 'admin'){
                return true;
            }else{
                //La classe courante est elle dans les classe pour lesquelle l'accès est autorisé à l'utilisateur ?
                return in_array($this->Classroom->id, $this->Session->read('Authorized')['classrooms']);
            }
        }else{
            //Si on a fourni le paramètre establishment_id
            if(isset($this->request->params['named']['establishment_id'])) {
                $establishment_id = intval($this->request->params['named']['establishment_id']);
                $this->Classroom->Establishment->id = $establishment_id;

                if ($this->Classroom->Establishment->exists()) {
                    $current_record = $this->Classroom->Establishment->read(array('user_id'));
                    if( ($current_record['Establishment']['user_id'] == $user['id']) || ($user['role'] === 'admin') )
                        return true;
                }
            }
        }
        return false;
    }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->set('title_for_layout', __('Visualiser une classe'));
		$this->Classroom->id = $id;
		$classroom = $this->Classroom->find('first', array(
			'conditions' => array('Classroom.id' => $id)
		));
		$this->set('classroom', $classroom);
		
		$classroompupil = $this->Classroom->ClassroomsPupil->find('all', array(
			'conditions' => array('ClassroomsPupil.classroom_id' => $id),
			'recursive' => -1,
			'fields' => array('Pupil.id','Pupil.first_name','Pupil.name','Pupil.sex','Pupil.birthday','Level.title'),
			'order' => array('Pupil.name','Pupil.first_name'),
			'joins' => array(
			    array('table' => 'levels',
			        'alias' => 'Level',
			        'type' => 'LEFT',
			        'conditions' => array(
			            'Level.id = ClassroomsPupil.level_id',
			        ),
			    ),
			    array('table' => 'pupils',
			        'alias' => 'Pupil',
			        'type' => 'LEFT',
			        'conditions' => array(
			            'Pupil.id = ClassroomsPupil.pupil_id',
			        ),
			    )
			 )
		));
		$this->set('ClassroomsPupil', $classroompupil);
	}
	
	public function viewtests($id = null){
		$this->set('title_for_layout', __('Visualiser une classe'));
		$this->Classroom->id = $id;

        if(isset($this->request->params['named']['periods']) && $this->request->params['named']['periods'] == 'all')
            $this->set('all', 'all');

		$this->Classroom->contain(array('Establishment.current_period_id'));
		$current_period = $this->Classroom->findById($id, 'Establishment.current_period_id');
		$current_period = $current_period['Establishment']['current_period_id'];
		
		if(isset($this->request->params['named']['periods']) && $this->request->params['named']['periods'] == 'all') {
			$this->Classroom->contain(array(
				'Evaluation.created DESC', 'Evaluation.unrated=0', 'Evaluation.User', 'Evaluation.Result', 
				'Evaluation.Pupil', 'Evaluation.Item', 'User', 'Establishment', 'Year'
			));
		}else{
			$this->Classroom->contain(array(
				'Evaluation.created DESC', 'Evaluation.period_id='.$current_period, 'Evaluation.unrated=0', 
				'Evaluation.User', 'Evaluation.Result', 'Evaluation.Pupil', 'Evaluation.Item', 'User', 
				'Establishment', 'Year'
			));
		}
		
		
		$classroom = $this->Classroom->find('first', array(
			'conditions' => array('Classroom.id' => $id)
		));
		$this->set('classroom', $classroom);
			
	}
	
	public function viewunrateditems($id = null){
		$this->set('title_for_layout', __('Visualiser une classe'));
		$this->Classroom->id = $id;

		$this->Classroom->contain(array('Evaluation.created DESC', 'Evaluation.unrated=1', 'Evaluation.Item', 'Evaluation.Period', 'User', 'Establishment', 'Year'));
		$classroom = $this->Classroom->find('first', array(
			'conditions' => array('Classroom.id' => $id)
		));
		$this->set('classroom', $classroom);

        $this->loadModel('Setting');
        $currentYear = $this->Setting->find('first', array('conditions' => array('Setting.key' => 'currentYear')));
		
		$periods = $this->Classroom->Evaluation->Period->find('list', array(
			'conditions' => array(
                'establishment_id' => $classroom['Classroom']['establishment_id'],
                'year_id' => $currentYear['Setting']['value']
            ),
			'recursive' => 0));
		$this->set('periods', $periods);
			
	}
	
	public function viewreports($id = null) {
		$this->set('title_for_layout', __('Bulletins d\'une classe'));
		$this->Classroom->id = $id;

		$this->Classroom->contain(array('User', 'Establishment', 'Year', 'Report'));
		$classroom = $this->Classroom->find('first', array(
			'conditions' => array('Classroom.id' => $id)
		));
		
		$this->set('classroom', $classroom);
		
		$periods = $this->Classroom->Evaluation->Period->find('list', array(
			'conditions' => array('establishment_id' => $classroom['Classroom']['establishment_id']),
			'recursive' => 0));
		$this->set('periods', $periods);
	}

	public function viewlpc($id = null, $palier = '', $pupil_id = '') {
		$this->set('title_for_layout', __('LPC d\'une classe'));

        $this->loadModel('LpcnodesPupil');
        $this->LpcnodesPupil->validateLPC();

		$this->Classroom->id = $id;
		$this->Classroom->contain(array('User', 'Establishment', 'Year', 'Report'));
		$classroom = $this->Classroom->find('first', array(
			'conditions' => array('Classroom.id' => $id)
		));

		$pupils = $this->Classroom->Evaluation->findPupilsByLevelsInClassroom($id);

		$this->set(compact('pupils', 'palier', 'pupil_id', 'classroom'));

		if((!empty($palier) && !empty($pupil_id)) && $this->Classroom->pupilIsLinkedToClassroom($id,$pupil_id)){
			$this->loadModel('Lpcnode');
			$lpc = $this->Lpcnode->getLPCforPupilId($pupil_id, $palier);
            $this->set('lpc', $lpc);
		}

		$this->set('classroom_id', $id);
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->set('title_for_layout', __('Ajouter une classe'));
		if ($this->request->is('post')) {
			$this->Classroom->create();
			if ($this->Classroom->save($this->request->data)) {
				$this->Session->setFlash(__('La nouvelle classe a été correctement ajoutée.'), 'flash_success');
				$this->redirect(array(
				    'controller'    => 'establishments',
				    'action'        => 'view', $this->request->data['Classroom']['establishment_id']));
			} else {
				$this->Session->setFlash(__('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.'), 'flash_error');
			}
		}
		
		//Si on a passé un establishment_id en paramètre, on présélectionne la liste déroulante avec la valeur passée.
		if(isset($this->request->params['named']['establishment_id']))
		    $this->set('establishment_id', $this->request->params['named']['establishment_id']);
        else
            $this->set('establishment_id', null);

        $this->loadModel('Setting');
        $currentYear = $this->Setting->find('first', array('conditions' => array('Setting.key' => 'currentYear')));
        $current_year = $currentYear['Setting']['value'];

		$establishments = $this->Classroom->Establishment->find('list');
		$pupils = $this->Classroom->Pupil->find('list');
		$users = $this->Classroom->User->find('list');
		$this->set(compact('users', 'years', 'establishments', 'pupils', 'current_year'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->set('title_for_layout', __('Modifier une classe'));
		$this->Classroom->id = $id;

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Classroom->save($this->request->data)) {
				$this->Session->setFlash(__('La classe a été correctement mise à jour'), 'flash_success');
				$this->redirect(array(
				    'controller'    => 'classrooms',
				    'action'        => 'view', $this->request->data['Classroom']['id']));
			} else {
				$this->Session->setFlash(__('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.'), 'flash_error');
			}
		} else {
			$this->request->data = $this->Classroom->read(null, $id);
		}
		$users = $this->Classroom->User->find('list');
		$years = $this->Classroom->Year->find('list');
		$establishments = $this->Classroom->Establishment->find('list');
		$pupils = $this->Classroom->Pupil->find('list');
		$users = $this->Classroom->User->find('list');
		$this->set(compact('users', 'years', 'establishments', 'pupils', 'users'));
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
		$this->Classroom->id = $id;

		if ($this->Classroom->delete()) {
			$this->Session->setFlash(__('Classroom deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Classroom was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
