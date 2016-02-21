<?php
namespace app\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\BadRequestException;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Datasource\Exception\RecordNotFoundException;
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

    function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow('getJson');
    }

    public function getJson($apikey = null, $classroom_id = null){

        $this->viewBuilder()->layout('ajax');

        $this->response->type(array('json' => 'application/json'));
        $this->response->type('json');

        $user = $this->Classrooms->Users->find('all', [
            'conditions' => [
                'apikey' => $this->request->params['pass'][0]
            ]
        ])->first();

        if(!isset($user))
            $json = ['error' => 'INVALID_APIKEY'];
        else{
            try{
                $classroom = $this->Classrooms->get($this->request->params['pass'][1],['contain' => ['Pupils', 'Pupils.Levels']]);

                if($user->role == 'admin' || $user->id === $classroom->user_id){
                    $json['error'] = 'NONE';
                    foreach($classroom->pupils as $pupil){
                        $json['pupils'][] = [
                            'id' => '*00' . $pupil->id . '*',
                            'name' => $pupil->name,
                            'first_name' => $pupil->first_name,
                            'birthday' => $pupil->birthday->i18nFormat('dd/MM/YYYY'),
                            'level' => $pupil->levels[0]->title
                        ];
                    }
                }
                else{
                    $json['error'] = 'UNAUTHORIZED';
                }
            }catch(RecordNotFoundException $e){
                $json['error'] = 'UNKNOWN_CLASSROOM';
            }
        }

        $this->set('json',json_encode($json,JSON_PRETTY_PRINT));
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

        $classroom = $this->Classrooms->get($id, ['contain' => 'Establishments']);
        $current_period = $classroom->establishment->current_period_id;
        $period = $this->Classrooms->Establishments->Periods->get($current_period);

        if(Time::now() > $period->end)
            $this->Flash->error('Il semblerait que la période sélectionnée soit inférieure à la date courante. Vous pouvez modifier cela en cliquant sur "établissement de la classe"');

        $contain = [
            'User', 'Establishments', 'Years',
            'Evaluations' => function (Query $q) use ($current_period) {
                return $q
                    ->where(['Evaluations.unrated' => '0'])
                    ->where(['Evaluations.period_id' => $current_period])
                    ->order(['Evaluations.created' => 'DESC']);
            },
            'Evaluations.Users', 'Evaluations.Items', 'Evaluations.Results', 'Evaluations.Pupils'
        ];

        if(isset($this->request->query['periods']) && $this->request->query['periods'] == 'all')
            $contain['Evaluations'] = function (Query $q) {
                return $q
                    ->where(['Evaluations.unrated' => '0'])
                    ->order(['Evaluations.created' => 'DESC']);
            };

        $classroom = $this->Classrooms->get($id, array(
            'contain' => $contain
        ));

        $this->set('classroom', $classroom);
	}
	
	public function viewunrateditems($id = null){
		$this->set('title_for_layout', __('Visualiser une classe'));
		$this->Classrooms->id = $id;

        $classroom = $this->Classrooms->get($id, [
            'contain' => ['User','Years','Establishments']
        ]);
		$this->set('classroom', $classroom);

        $this->loadModel('Settings');
        $currentYear = $this->Settings->find('all', array('conditions' => array('Settings.key' => 'currentYear')))->first();
		
		$periods = $this->Classrooms->Evaluations->Periods->find('list', array(
			'conditions' => array(
                'establishment_id' => $classroom['Classrooms']['establishment_id'],
                'year_id' => $currentYear['Settings']['value']
            ),
			'recursive' => 0));
		$this->set('periods', $periods);
			
	}
	
	public function viewreports($id = null) {
		$this->set('title_for_layout', __('Bulletins d\'une classe'));

		$classroom = $this->Classrooms->get($id, ['contain' => ['User', 'Establishments', 'Years', 'Reports']]);
		$this->set('classroom', $classroom);
		
		$periods = $this->Classrooms->Evaluations->Periods->find('list', array(
			'conditions' => array('establishment_id' => $classroom->establishment_id)))->toArray();
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
                    'action'        => 'view', $classroom->establishment_id));
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
