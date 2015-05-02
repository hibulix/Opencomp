<?php
namespace app\Controller;

use App\Controller\AppController;

/**
  * AcademiesController.php
  *
  * PHP version 5
  *
  * @category Controller
  * @package  Opencomp
  * @author   Jean Traullé <jtraulle@gmail.com>
  * @license  http://www.opensource.org/licenses/agpl-v3 The Affero GNU General Public License
  * @link     http://www.opencomp.fr
  */

/**
 * Contrôleur de gestion des académies
 *
 * @category Controller
 * @package  Opencomp
 * @author   Jean Traullé <jtraulle@gmail.com>
 * @license  http://www.opensource.org/licenses/agpl-v3 The Affero GNU General Public License
 * @link     http://www.opencomp.fr
 */
class AcademiesController extends AppController {

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
	    $this->set('title_for_layout', __('Liste des académies'));
		$this->set('academies', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
	    $this->set('title_for_layout', __('Visualiser une académie'));
		$this->Academy->id = $id;
		if (!$this->Academy->exists()) {
			throw new NotFoundException(__('L\'académie demandée n\'existe pas !'), 'flash_error');
		}
		$this->set('academy', $this->Academy->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
	    $this->set('title_for_layout', __('Ajouter une académie'));
		if ($this->request->is('post')) {
			$this->Academy->create();
			if ($this->Academy->save($this->request->data)) {
				$this->Flash->success('La nouvelle académie a été correctement ajoutée');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
			}
		}
		$users = $this->Academy->User->find('list');
		$this->set(compact('users'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
	    $this->set('title_for_layout', __('Modifier une académie'));
		$this->Academy->id = $id;
		if (!$this->Academy->exists()) {
			throw new NotFoundException(__('L\'académie demandée n\'existe pas !'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Academy->save($this->request->data)) {
				$this->Flash->success('L\'académie a été correctement mise à jour');
				$this->redirect(array('action' => 'view', $this->data['Academy']['id']));
			} else {
				$this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
			}
		} else {
			$this->request->data = $this->Academy->read(null, $id);
		}
		$users = $this->Academy->User->find('list');
		$this->set(compact('users'));
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
		$this->Academy->id = $id;
		if (!$this->Academy->exists()) {
			throw new NotFoundException(__('L\'académie demandée n\'existe pas !'));
		}
		if ($this->Academy->delete()) {
			$this->Flash->success('L\'académie a été correctement supprimée');
			$this->redirect(array('action' => 'index'));
		}
		$this->Flash->error('L\'académie n\'a pas pu être supprimée');
		$this->redirect(array('action' => 'index'));
	}
}
