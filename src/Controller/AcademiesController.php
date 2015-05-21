<?php
namespace app\Controller;

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
		$academy = $this->Academies->get($id, [
            'contain' => ['Establishments', 'Users']
        ]);
        $this->set('academy', $academy);
	}

/**
 * add method
 *
 * @return \Cake\Network\Response|null
 */
	public function add() {
	    $this->set('title_for_layout', __('Ajouter une académie'));

        $academy = $this->Academies->newEntity();
        if ($this->request->is('post')) {
            $academy = $this->Academies->newEntity($this->request->data);
            if ($this->Academies->save($academy)) {
                $this->Flash->success('La nouvelle académie a été correctement ajoutée.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
            }
        }
		$users = $this->Academies->Users->find('list');
		$this->set(compact('academy' ,'users'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return \Cake\Network\Response|null
 */
	public function edit($id = null) {
		$this->set('title_for_layout', __('Modifier une académie'));
		$academy = $this->Academies->get($id, [
			'contain' => ['Users']
		]);
		if ($this->request->is(['patch', 'post', 'put'])) {
			$academy = $this->Academies->patchEntity($academy, $this->request->data);
			if ($this->Academies->save($academy)) {
				$this->Flash->success('L\'académie a été correctement mise à jour');
				return $this->redirect(['action' => 'index']);
			} else {
				$this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
			}
		}
		$users = $this->Academies->Users->find('list')->toArray();
		$this->set(compact('academy', 'users'));
	}

/**
 * delete method
 *
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 * @param string $id
 * @return \Cake\Network\Response|null
 */
	public function delete($id = null) {
        $academy = $this->Academies->get($id);
        $this->request->allowMethod(['post', 'delete']);
        if ($this->Academies->delete($academy)) {
            $this->Flash->success('L\'académie a été correctement supprimée');
        } else {
            $this->Flash->error('L\'académie n\'a pas pu être supprimée');
        }
        return $this->redirect(['action' => 'index']);
	}
}
