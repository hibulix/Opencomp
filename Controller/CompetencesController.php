<?php
App::uses('AppController', 'Controller');
/**
 * Competences Controller
 *
 */
class CompetencesController extends AppController {

	public $helpers = array('Tree');

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
				$this->Session->setFlash(__('La nouvelle compétence a été correctement ajoutée'), 'flash_success');
				$this->redirect(array('action' => 'add', $this->request->data['Competence']['parent_id']));
			} else {
				$this->Session->setFlash(__('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.'), 'flash_error');
			}
		}

		//On passe le paramètre à la vue
		if(isset($id) && is_numeric($id))
			$this->set('idcomp', $id);

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

		//On vérifie qu'un paramètre nommé classroom_id a été fourni et qu'il existe.
		if(isset($this->request->params['named']['evaluation_id'])) {
       		$evaluation_id = intval($this->request->params['named']['evaluation_id']);
       		$this->set('evaluation_id', $evaluation_id);
       		$this->Competence->Item->Evaluation->id = $evaluation_id;
			if (!$this->Competence->Item->Evaluation->exists()) {
				throw new NotFoundException(__('The evaluation_id provided does not exist !'));
			}else{
				$evaluation = $this->Competence->Item->Evaluation->find('first', array(
					'conditions' => array('Evaluation.id' => $evaluation_id),
					'recursive' => -1
				));
				$this->set('eval', $evaluation);
			}
		} else {
			throw new NotFoundException(__('You must provide a evaluation_id in order to attach an item to this test !'));
		}
    }

    public function attachunrateditem() {
        $this->set('title_for_layout', __('Associer un item non évalué à une évaluation'));

	    if(!$this->request->is('post') || !is_numeric($this->request->data['Classroom']['period_id']))
		    throw new NotFoundException(__('Missing or invalid arguments !'));
	    else{
	    	$this->set('period_id', $this->request->data['Classroom']['period_id']);
	    	$this->set('classroom_id', $this->request->data['Classroom']['classroom_id']);
	    }
	}

    public function returnCompetences($id = null){

        if(isset($this->request->query['id']) && $this->request->query['id'] != "#")
            $id = $this->request->query['id'];

        $this->layout = 'pdf';

        $items_enfants = $this->Competence->Item->findItemsWithCompetenceId($id);
        $resultats['Items'] = $items_enfants;

        $competences_enfants = $this->Competence->findAllCompetencesWithParentId($id);
        $resultats['Competences'] = $competences_enfants;

        $this->set('items_competences', $resultats);
    }

    public function index() {
    	$this->set('title_for_layout', __('Référentiel de compétences'));
        $this->Competence->recursive = -1;
        $stuff = $this->Competence->find('threaded',
        	array(
            	'order' => 'lft ASC',
            )
        );
        $this->set('stuff', $stuff);
    }


}
