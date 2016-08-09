<?php
namespace app\Controller;

use /** @noinspection PhpUnusedAliasInspection */
    App\Controller\AppController;
use Cake\Network\Exception\NotFoundException;
/**
 * Competences Controller
 *
 */
class CompetencesController extends AppController {

	public $components = array('JsonTree');

	public function isAuthorized($user = null) {
		if (in_array($this->action, array('add', 'moveup', 'movedown', 'deletenode'))) {
			if($user['role'] === 'admin')
				return true;
			else
				return false;
		}else{
			return true;
		}
	}

	public function add($id = null) {
		$this->set('title_for_layout', __('Ajouter une compétence au référentiel'));
		if ($this->request->is('post')) {
			$this->Competence->create();
			if ($this->Competence->save($this->request->data)) {
				$this->Flash->success('La nouvelle compétence a été correctement ajoutée');
				$this->redirect(array('action' => 'add', $this->request->data['Competence']['parent_id']));
			} else {
				$this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
			}
		}

		//On passe le paramètre à la vue
		if(isset($id) && is_numeric($id))
			$this->set('idcomp', $id);

		//Récupération des ids des catégories existantes
		$competenceids = $this->Competence->generateTreeList(null, null, null, "");
		$this->set('cid', $competenceids);
	}

    /**
     * edit method
     *
     * @param null $id
     * @throws NotFoundException
     */
	public function edit($id = null)
	{
		$this->set('title_for_layout', __('Modifier une compétence'));

		$this->Competence->id = $id;
		if (!$this->Competence->exists()) {
			throw new NotFoundException(__('Cette compétence n\'existe pas ;)'));
		}

		$competence = $this->Competence->findById($id);
		if (!$this->request->data) {
			$this->request->data = $competence;
		}

		if ($this->request->is('post')) {
			if ($this->Competence->save($this->request->data)) {
				$this->Flash->success('La compétence a été correctement modifée');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
			}
		}

		//Récupération des ids des catégories existantes
		$competenceids = $this->Competence->generateTreeList(null, null, null, "");
		$this->set('cid', $competenceids);
	}

	public function moveup($id = null) {
	    $this->Competence->id = $id;
	    if (!$this->Competence->exists()) {
	       throw new NotFoundException(__('Cette compétence n\'existe pas ;)'));
	    }

	    $this->Competence->moveUp($this->Competence->id, 1);
	    $this->redirect(array('action' => 'index'));
	}

	public function movedown($id = null) {
	    $this->Competence->id = $id;
	    if (!$this->Competence->exists()) {
	       throw new NotFoundException(__('Cette compétence n\'existe pas ;)'));
	    }

	    $this->Competence->moveDown($this->Competence->id, 1);
	    $this->redirect(array('action' => 'index'));
	}

	public function deleteNode($id = null) {
	    $this->Competence->id = $id;
	    if (!$this->Competence->exists()) {
	       throw new NotFoundException(__('Cette compétence n\'existe pas ;)'));
	    }

		$this->Competence->delete();
	    $this->redirect(array('action' => 'index'));
	}

	public function attachitem() {
        $this->set('title_for_layout', __('Associer un item à une évaluation'));

        $evaluation = $this->Competences->Items->Evaluations->get($this->request->query['evaluation_id']);

        $this->set('eval', $evaluation);
        $this->set('json',$this->JsonTree->allItemsToJson());
    }

    public function attachunrateditem() {
        $this->set('title_for_layout', __('Associer un item non évalué à une évaluation'));

	    if(!$this->request->is('post') || !is_numeric($this->request->data['Classroom']['period_id']))
		    throw new NotFoundException(__('Missing or invalid arguments !'));
	    else{
	    	$this->set('period_id', $this->request->data['Classroom']['period_id']);
	    	$this->set('classroom_id', $this->request->data['Classroom']['classroom_id']);
            $this->set('json',$this->JsonTree->allItemsToJson());
	    }
	}

    public function index() {
    	$this->set('title_for_layout', __('Référentiel de compétences'));
		$this->set('json',$this->JsonTree->allItemsToJson());
    }


}
