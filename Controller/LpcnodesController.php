<?php
App::uses('AppController', 'Controller');
/**
 * Lpcnodes Controller
 *
 * @property Lpcnode $Lpcnode
 * @property PaginatorComponent $Paginator
 */
class LpcnodesController extends AppController {

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array('Tree');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->set('title_for_layout', __('Livret Personnel de Compétences'));
        $this->Lpcnode->recursive = -1;
        $stuff = $this->Lpcnode->find('threaded',
        	array(
            	'order' => 'lft ASC',
            )
        ); 
        $this->set('stuff', $stuff); 
	}

    public function return_nodes($id = null) {

        if(isset($this->request->query['id']) && $this->request->query['id'] != "#")
            $id = $this->request->query['id'];

        $this->layout = 'pdf';

        $lpcnodes_enfants = $this->Lpcnode->findAllNodesWithParentId($id);
        $resultats['Competences'] = $lpcnodes_enfants;

        $this->set('items_lpcnodes', $resultats);

    }

	public function admin_moveup($id = null) {
	    $this->Lpcnode->id = $id;
	    if (!$this->Lpcnode->exists()) {
	       throw new NotFoundException(__('Ce noeud n\'existe pas ;)'));
	    }

	    $this->Lpcnode->moveUp($this->Competence->id, 1);
	    $this->redirect(array('action' => 'index'));
	}
	
	public function admin_movedown($id = null) {
	    $this->Lpcnode->id = $id;
	    if (!$this->Lpcnode->exists()) {
	       throw new NotFoundException(__('Ce noeud n\'existe pas ;)'));
	    }

	    $this->Lpcnode->moveDown($this->Lpcnode->id, 1);
	    $this->redirect(array('action' => 'index'));
	}
	
	public function admin_deleteNode($id = null) {
	    $this->Lpcnode->id = $id;
	    if (!$this->Lpcnode->exists()) {
	       throw new NotFoundException(__('Ce noeud n\'existe pas ;)'));
	    }

		$this->Lpcnode->delete();
	    $this->redirect(array('action' => 'index'));
	}

/**
 * add method
 *
 * @return void
 */
	public function admin_add($id = null) {
		$this->set('title_for_layout', __('Ajouter un noeud au Livret Personnel de Compétences'));
		if ($this->request->is('post')) {
			$this->Lpcnode->create();
			if ($this->Lpcnode->save($this->request->data)) {
				$this->Session->setFlash(__('La nouvelle compétence a été correctement ajoutée'), 'flash_success');
				if(isset($this->request->data['Lpcnode']['parent_id']))
					$this->redirect(array('action' => 'add', $this->request->data['Lpcnode']['parent_id']));
				else
					$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.'), 'flash_error');
			}
		}
		
		//On passe le paramètre à la vue
		if(isset($id) && is_numeric($id))
			$this->set('idnode', $id);
		
		//Récupération des ids des catégories existantes	
		$competenceids = $this->Lpcnode->generateTreeList(null, null, null, "");
		$this->set('cid', $competenceids);
	}
}
