<?php
namespace app\Controller;

use App\Controller\AppController;
use Cake\Auth\WeakPasswordHasher;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\MethodNotAllowedException;
use Cake\Utility\Security;

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

		$this->viewBuilder()->layout('auth');

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
		$this->set('users', $this->paginate());
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
	    $this->set('title_for_layout', __('Ajouter un utilisateur'));
		$user = $this->Users->newEntity();

		if ($this->request->is('post')) {
            $user = $this->Users->newEntity($this->request->data);
            $user->set('role','');
			if ($this->Users->save($user)) {
				$this->Flash->success('Le nouvel utilisateur a été correctement ajouté');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
			}
		}
		$academies = $this->Users->Academies->find('list');
		$classrooms = $this->Users->Classrooms->find('list');
		$establishments = $this->Users->Establishments->find('list');
		$this->set(compact('academies', 'classrooms', 'establishments', 'user'));
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

        $user = $this->Users->get($id,['contain'=>['Establishments','Academies','Classrooms']]);
		if ($this->request->is('post') || $this->request->is('put')) {
            $user = $this->Users->patchEntity($user, $this->request->data);
			if ($this->Users->save($user)) {
				$this->Flash->success('L\'utilisateur a été correctement mis à jour');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
			}
		}
		$academies = $this->Users->Academies->find('list');
		$classrooms = $this->Users->Classrooms->find('list');
		$establishments = $this->Users->Establishments->find('list');
		$this->set(compact('academies', 'classrooms', 'establishments', 'user'));
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
        $user = $this->Users->get($id);
		if ($this->Users->delete($user)) {
			$this->Flash->success('L\'utilisateur a été correctement supprimé');
		}else{
            $this->Flash->error('L\'utilisateur n\'a pas pu être supprimé');
        }
        $this->redirect(array('action' => 'index'));
	}
}
