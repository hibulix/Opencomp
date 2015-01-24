<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('needYubikeyToken');
    }

    public function needYubikeyToken(){
        $this->autoRender = false;
        
        if($this->request->is('post')) {
            $this->layout = 'pdf';

            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.username' => $this->request->data['User']['username'],
                    'User.password' => $this->Auth->password($this->request->data['User']['password'])
                ),
                'recursive' => -1
            ));
            if (isset($user['User']['yubikeyID']) && !empty($user['User']['yubikeyID'])){
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
			$user = $this->User->find('first', array(
		        'conditions' => array(
		        	'User.username' => $this->request->data['User']['username'],
		        	'User.password' => $this->Auth->password($this->request->data['User']['password'])
		        ),
		        'recursive' => -1
		    ));

			if(isset($user) && !empty($user)){
				if(isset($user['User']['yubikeyID']) && !empty($user['User']['yubikeyID'])){
					if($user['User']['yubikeyID'] == substr($this->request->data['User']['yubikeyOTP'], 0, 12)){
						 $this->loadModel('Setting');
						 $otp = $this->request->data['User']['yubikeyOTP'];

						 $clientID = $this->Setting->find('first', array('conditions' => array('Setting.key' => 'yubikeyClientID')));
						 $secret = $this->Setting->find('first', array('conditions' => array('Setting.key' => 'yubikeySecretKey')));
						 
						 $v = new \Yubikey\Validate($secret['Setting']['value'], $clientID['Setting']['value']);
						 $response = $v->check($otp);
						 
						  if ($response->success() === true) {
						  	$this->Auth->login();
                            $this->setAuthorizedClassroomsId();
							return $this->redirect($this->Auth->redirect());
						  } else {
						    $this->Session->setFlash("YubikeyOTP invalide !", "flash_error");
						  }
					}else{
						$this->Session->setFlash("YubikeyID invalide !", "flash_error");
					}
				}else{
					$this->Auth->login();
                    $this->setAuthorizedClassroomsId();
					return $this->redirect($this->Auth->redirect());
				}
			}else{
				$this->Session->setFlash("Votre login ou votre mot de passe ne correspond pas !", "flash_error"); 
			}				
		}
	}
	
	public function setAuthorizedClassroomsId(){
        $this->Session->write('Authorized',$this->User->findAuthorizedClasses($this->Auth->user('id')));
	}
	
	public function logout(){
		$this->Auth->logout();
		$this->Session->setFlash('Vous êtes maintenant déconnecté.','flash_success');
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
				$this->Session->setFlash(__('Le nouvel utilisateur a été correctement ajouté'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.'), 'flash_error');
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
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('L\'utilisateur demandé n\'existe pas !'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('L\'utilisateur a été correctement mis à jour'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.'), 'flash_error');
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
		}
		$academies = $this->User->Academy->find('list');
		$classrooms = $this->User->Classroom->find('list');
		$competences = $this->User->Competence->find('list');
		$establishments = $this->User->Establishment->find('list');
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
			$this->Session->setFlash(__('L\'utilisateur a été correctement supprimé'), 'flash_success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('L\'utilisateur n\'a pas pu être supprimé'), 'flash_error');
		$this->redirect(array('action' => 'index'));
	}
}
