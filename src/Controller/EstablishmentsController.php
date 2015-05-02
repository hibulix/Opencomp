<?php
namespace app\Controller;

use App\Controller\AppController;

/**
  * EstablishmentsController.php
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
 * Contrôleur de gestion des établissements scolaires
 *
 * @category Controller
 * @package  Opencomp
 * @author   Jean Traullé <jtraulle@gmail.com>
 * @license  http://www.opensource.org/licenses/agpl-v3 The Affero GNU General Public License
 * @link     http://www.opencomp.fr
 */
class EstablishmentsController extends AppController {

	public $helpers = array('Time');

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
	    $this->set('title_for_layout', __('Visualiser un établissement scolaire'));
		$this->Establishment->id = $id;
		if (!$this->Establishment->exists()) {
			throw new NotFoundException(__('L\'établissement scolaire demandé n\'existe pas !'), 'flash_error');
		}

        $this->loadModel('Setting');
        $currentYear = $this->Setting->find('first', array('conditions' => array('Setting.key' => 'currentYear')));

        //On récupère l'établissement, les classes et les périodes correspondant à l'année courante.
        $establishment = $this->Establishment->find('first',
            array(
                'conditions' => array('Establishment.id' => $id),
                'contain' => array(
                    'User',
                    'Academy',
                    'Period' => array(
                        'conditions' => array('Period.year_id =' => $currentYear['Setting']['value'])),
                    'Period.Year',
                    'Classroom' => array(
                        'conditions' => array('Classroom.year_id =' => $currentYear['Setting']['value'])),
                    'Classroom.User',
                    'Classroom.Year'
                )
            )
        );

		$this->set('establishment', $establishment);
		$this->set('current_year', $currentYear['Setting']['value']);
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
	    $this->set('title_for_layout', __('Ajouter un établissement scolaire'));
		if ($this->request->is('post')) {
			$this->Establishment->create();
			if ($this->Establishment->save($this->request->data)) {
				$this->Flash->success('Le nouvel établissement scolaire a été correctement ajouté');
				$this->redirect(array(
				    'controller'    => 'academies',
				    'action'        => 'view', $this->request->data['Establishment']['academy_id']));
			} else {
				$this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
			}
		}
		
		//Si on a passé un academy_id en paramètre, on présélectionne la liste déroulante avec la valeur passée.
		if(isset($this->request->params['named']['academy_id']))
		    $this->set('academy_id', $this->request->params['named']['academy_id']);
        else
            $this->set('academy_id', null);
		
		$users = $this->Establishment->User->find('list');
		$academies = $this->Establishment->Academy->find('list');
		$this->set(compact('users', 'academies'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
	    $this->set('title_for_layout', __('Modifier un établissement scolaire'));
		$this->Establishment->id = $id;
		if (!$this->Establishment->exists()) {
			throw new NotFoundException(__('L\'établissement scolaire demandé n\'existe pas !'), 'flash_error');
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Establishment->save($this->request->data)) {
				$this->Flash->success('L\'établissement scolaire a été correctement mis à jour');
				$this->redirect(array(
				    'controller'    => 'academies',
				    'action'        => 'view', $this->request->data['Establishment']['academy_id']));
			} else {
				$this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
			}
		} else {
			$this->request->data = $this->Establishment->read(null, $id);
		}
		$users = $this->Establishment->User->find('list');
		$academies = $this->Establishment->Academy->find('list');
		$this->set(compact('users', 'academies'));
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
		$this->Establishment->id = $id;
		if (!$this->Establishment->exists()) {
			throw new NotFoundException(__('L\'établissement scolaire demandé n\'existe pas !'), 'flash_error');
		}
		if ($this->Establishment->delete()) {
			$this->Flash->success('L\'établissement scolaire a été correctement supprimé');
			$this->redirect(array('action' => 'index'));
		}
		$this->Flash->error('L\'établissement scolaire n\'a pas pu être supprimé');
		$this->redirect(array('action' => 'index'));
	}

    public function setDefaultPeriod() {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Establishment->id = $this->request->data['Establishment']['establishment_id'];
        if (!$this->Establishment->exists()) {
            throw new NotFoundException(__('L\'établissement scolaire demandé n\'existe pas !'), 'flash_error');
        }
        $this->Establishment->read(null, $this->request->data['Establishment']['establishment_id']);
        $this->Establishment->set('current_period_id', $this->request->data['Establishment']['current_period_id']);
        $this->Establishment->save();

        $this->Flash->success('La période courante de l\'établissement a bien été modifiée.');
        $this->redirect(array('action' => 'view', $this->request->data['Establishment']['establishment_id']));
    }
}
