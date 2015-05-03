<?php
namespace app\Controller;

use App\Controller\AppController;
use Cake\Network\Exception\BadRequestException;
use Cake\ORM\TableRegistry;
/**
 * Classrooms Controller
 *
 * @property Classroom $Classroom
 */
class ClassroomsController extends AppController {

    /**
     * Fonction permettant de déterminer les droits d'accès à une classe
     *
     * @param null $user
     * @return bool
     */
    public function isAuthorized($user = null) {
        if(isset($this->request->params['pass'][0])){
            if($user['role'] === 'admin'){
                return true;
            }else{
                //La classe courante est elle dans les classe pour lesquelle l'accès est autorisé à l'utilisateur ?
                $classroom = $this->Classrooms->get($this->request->params['pass'][0]);
                return  in_array($classroom->id, $this->request->session()->read('Authorized')['classrooms']) ||
                        in_array($classroom->id, $this->request->session()->read('Authorized')['classrooms_manager']);
            }
        }else{
            //Si on a fourni le paramètre establishment_id
            if(isset($this->request->query['establishment_id'])) {
                $establishment_id = intval($this->request->query['establishment_id']);
                $establishment = $this->Classrooms->Establishments->get($establishment_id);

                if( ($establishment->user_id == $user['id']) || ($user['role'] === 'admin') )
                    return true;
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

        $classroom = $this->Classrooms->get($id,[
            'contain' => ['User', 'Users', 'Establishments', 'Years']
        ]);

        $this->ClassroomsPupils = TableRegistry::get('ClassroomsPupils');
		$classroomsPupils = $this->ClassroomsPupils->returnPupilsWithLevelsForClassroom($id);

		$this->set(compact('classroom','classroomsPupils'));

	}
	
	public function viewtests($id = null){
		$this->set('title_for_layout', __('Visualiser une classe'));
		$this->Classroom->id = $id;

        if(isset($this->request->query['periods']) && $this->request->params['named']['periods'] == 'all')
            $this->set('all', 'all');

		$this->Classroom->contain(array('Establishment.current_period_id'));
		$current_period = $this->Classroom->findById($id, 'Establishment.current_period_id');
		$current_period = $current_period['Establishment']['current_period_id'];
		
		$period = $this->Classroom->Establishment->Period->find('first', array(
			'conditions' => array('Period.id' => $current_period),
			'recursive' => 0
		));
		
		$datefinperiode = new DateTime($period['Period']['end']);
		$datecourante = new DateTime('now');

		if($datecourante > $datefinperiode)
			$this->Flash->error('Il semblerait que la période sélectionnée soit inférieure à la date courante. Vous pouvez modifier cela en cliquant sur "établissement de la classe"');
			
		
		if(isset($this->request->query['periods']) && $this->request->params['named']['periods'] == 'all') {
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

    /**
     * add method
     *
     * @return void
     */
	public function add() {
		$this->set('title_for_layout', __('Ajouter une classe'));

        if(is_null($this->request->query['establishment_id']))
            throw new BadRequestException;

        $establishment = $this->Classrooms->Establishments->get($this->request->query['establishment_id']);

        $classroom = $this->Classrooms->newEntity();
		if ($this->request->is('post')) {
            $classroom = $this->Classrooms->newEntity($this->request->data);

            $this->Settings = TableRegistry::get('Settings');
            $currentYear = $this->Settings->find('all', array('conditions' => array('Settings.key' => 'currentYear')))->first();
            $current_year = $currentYear->value;

            $classroom->year_id = $current_year;
            $classroom->establishment_id = $establishment->id;

			if ($this->Classrooms->save($classroom)) {
				$this->Flash->success('La nouvelle classe a été correctement ajoutée.');
				$this->redirect(array(
				    'controller'    => 'establishments',
				    'action'        => 'view', $classroom->establishment_id));
			} else {
				$this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
			}
		}

		$users = $this->Classrooms->Users->find('list');
        $this->set('establishment_id', $establishment->id);
		$this->set(compact('classroom', 'users', 'establishments'));
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
        $classroom = $this->Classrooms->get($id, [
            'contain' => 'Users'
        ]);

		if ($this->request->is(['patch', 'post', 'put'])) {
            $classroom = $this->Classrooms->patchEntity($classroom, $this->request->data);
            if ($this->Classrooms->save($classroom)) {
                $this->Flash->success('La classe a été correctement modifiée.');
                $this->redirect(array(
                    'controller'    => 'classrooms',
                    'action'        => 'view', $classroom->id));
            } else {
                $this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
            }
		}

		$users = $this->Classrooms->Users->find('list');
		$this->set(compact('classroom', 'users'));
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
