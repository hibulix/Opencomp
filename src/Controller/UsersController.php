<?php
namespace app\Controller;

use App\Controller\AppController;
use Cake\Auth\WeakPasswordHasher;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\MethodNotAllowedException;

/**
 * Users Controller
 *
 * @property User $User
 * @property bool|object Users
 * @property string layout
 * @property bool|object action
 */
class UsersController extends AppController {

    function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow('needYubikeyToken');
    }

    public function needYubikeyToken(){
        $this->autoRender = false;

        if($this->request->is('post')) {
            $this->layout = 'pdf';

            $user = $this->Users->find('all', array(
                'conditions' => array(
                    'Users.username' => $this->request->data['username'],
                    'Users.password' => (new WeakPasswordHasher)->hash($this->request->data['password'])
                )
            ))->first();
            if (isset($user->yubikeyID) && !empty($user->yubikeyID)){
                echo'true';
                return;
            }

        }
        echo 'false';
        return;
    }

	public function login(){
		$this->set('title_for_layout', __('Identification requise'));
		$iduser = $this->Auth->user('id');
		if(!empty($iduser))
			$this->redirect(array('controller' => 'dashboard', 'action' => 'index'));

		$this->layout = 'auth';
		if($this->request->is('post')){

			$users = $this->Users->find('all', array(
		        'conditions' => array(
		        	'Users.username' => $this->request->data['username'],
		        	'Users.password' => (new WeakPasswordHasher)->hash($this->request->data['password'])
		        ),
		        'recursive' => -1
		    ));
			$user = $users->first();

			if(isset($user) && !empty($user)){
				if(isset($user->yubikeyID) && !empty($user->yubikeyID)){
					if($user->yubikeyID == substr($this->request->data['yubikeyOTP'], 0, 12)){
						 $otp = $this->request->data['yubikeyOTP'];

                         $settingsTable = TableRegistry::get('Settings');
						 $clientID = $settingsTable->find('all', array('conditions' => array('Settings.key' => 'yubikeyClientID')))->first();
						 $secret = $settingsTable->find('all', array('conditions' => array('Settings.key' => 'yubikeySecretKey')))->first();

						 $v = new \Yubikey\Validate($secret->value, $clientID->value);
						 $response = $v->check($otp);

						  if ($response->success() === true) {
							  $this->Auth->setUser($user->toArray());
                              $this->setAuthorizedClassroomsId();
							  return $this->redirect($this->Auth->redirectUrl());
						  } else {
						    $this->Flash->error('YubikeyOTP invalide !');
						  }
					}else{
						$this->Flash->error('YubikeyID invalide !');
					}
				}else{
					$this->Auth->setUser($user->toArray());
                    $this->setAuthorizedClassroomsId();
					return $this->redirect($this->Auth->redirectUrl());
				}
			}else{
				$this->Flash->error('Votre login ou votre mot de passe ne correspond pas !');
			}
		}
	}

	public function setAuthorizedClassroomsId(){
        $this->request->session()->write('Authorized',$this->Users->findAuthorizedClasses($this->Auth->user('id')));
	}

	public function logout(){
		$this->Auth->logout();
		$this->Flash->success('Vous êtes maintenant déconnecté.');
		$this->redirect(array('controller' => 'users', 'action' => 'login'));
	}

	public function isAuthorized($user = null) {
		if (in_array($this->action, array('index', 'add', 'edit', 'delete'))) {
			if($user['role'] === 'admin')
				return true;
			else
				return false;
		}else{
			return true;
		}
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
        $this->set('title_for_layout', __('Liste des utilisateurs'));
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
	    $this->set('title_for_layout', __('Détail d\'utilisateur'));
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('L\'utilisateur demandé n\'existe pas !'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
	    $this->set('title_for_layout', __('Ajouter un utilisateur'));
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Flash->success('Le nouvel utilisateur a été correctement ajouté');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
			}
		}
		$academies = $this->User->Academy->find('list');
		$classrooms = $this->User->Classroom->find('list');
		$competences = $this->User->Competence->find('list');
		$establishments = $this->User->Establishment->find('list');
		$this->set(compact('academies', 'classrooms', 'competences', 'establishments'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
	    $this->set('title_for_layout', __('Modifier un utilisateur'));
		$this->Users->id = $id;
		if (!$this->Users->exists()) {
			throw new NotFoundException(__('L\'utilisateur demandé n\'existe pas !'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Users->save($this->request->data)) {
				$this->Flash->success('L\'utilisateur a été correctement mis à jour');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
			}
		} else {
			$this->request->data = $this->Users->read(null, $id);
		}
		$academies = $this->Users->Academy->find('list');
		$classrooms = $this->Users->Classroom->find('list');
		$competences = $this->Users->Competence->find('list');
		$establishments = $this->Users->Establishment->find('list');
		$this->set(compact('academies', 'classrooms', 'competences', 'establishments'));
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
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('L\'utilisateur demandé n\'existe pas !'));
		}
		if ($this->User->delete()) {
			$this->Flash->success('L\'utilisateur a été correctement supprimé');
			$this->redirect(array('action' => 'index'));
		}
		$this->Flash->error('L\'utilisateur n\'a pas pu être supprimé');
		$this->redirect(array('action' => 'index'));
	}
}
