<?php
namespace app\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

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

        $this->Settings = TableRegistry::get('Settings');
        $currentYear = $this->Settings->find('all', array('conditions' => array('Settings.key' => 'currentYear')))->first();

        //On récupère l'établissement, les classes et les périodes correspondant à l'année courante.
        $establishment = $this->Establishments->get($id,
            array(
                'contain' => array(
                    'User',
                    'Users',
                    'Academies',
                    'Periods' => array(
                        'conditions' => array('Periods.year_id =' => $currentYear->value)),
                    'Periods.Years',
                    'Classrooms' => array(
                        'conditions' => array('Classrooms.year_id =' => $currentYear->value)),
                    'Classrooms.User',
                    'Classrooms.Years'
                )
            )
        );

        $this->set('blank_period', $this->Establishments->Periods->newEntity());
		$this->set('establishment', $establishment);
		$this->set('current_year', $currentYear->value);
	}

    /**
     * add method
     *
     * @return \Cake\Network\Response|null
     */
	public function add() {
	    $this->set('title_for_layout', __('Ajouter un établissement scolaire'));

        $establishment = $this->Establishments->newEntity();
        if ($this->request->is('post')) {
            $establishment = $this->Establishments->newEntity($this->request->data);
            if ($this->Establishments->save($establishment)) {
                $this->Flash->success('Le nouvel établissement scolaire a été correctement ajouté.');
                return $this->redirect(['controller' => 'Academies', 'action' => 'view', $establishment->academy_id]);
            } else {
                $this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
            }
        }

        //Si on a passé un academy_id en paramètre, on présélectionne la liste déroulante avec la valeur passée.
        if(isset($this->request->query['academy_id']))
            $this->set('academy_id', $this->request->query['academy_id']);
        else
            $this->set('academy_id', null);

        $users = $this->Establishments->Users->find('list');
        $academies = $this->Establishments->Academies->find('list');
        $this->set(compact('users', 'academies', 'establishment'));
	}

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return \Cake\Network\Response|null
     */
	public function edit($id = null) {
	    $this->set('title_for_layout', __('Modifier un établissement scolaire'));

        $establishment = $this->Establishments->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $establishment = $this->Establishments->patchEntity($establishment, $this->request->data);
            if ($this->Establishments->save($establishment)) {
                $this->Flash->success('L\'établissement a été correctement mis à jour');
                return $this->redirect(['controller' => 'Academies', 'action' => 'view', $establishment->academy_id]);
            } else {
                $this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
            }
        }
        $users = $this->Establishments->Users->find('list')->toArray();
        $academies = $this->Establishments->Academies->find('list');
        $this->set(compact('establishment', 'academies', 'users'));

	}

    public function setDefaultPeriod($id = null) {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $establishment = $this->Establishments->get($id);
            $establishment->period_id = $this->request->data('period_id');
            if ($this->Establishments->save($establishment)) {
                $this->Flash->success('La nouvelle période courante a bien été sauvegardée.');
            } else {
                $this->Flash->error('Une erreur est survenue');
            }
            return $this->redirect(['action' => 'view', $establishment->id]);
        }
    }
}
