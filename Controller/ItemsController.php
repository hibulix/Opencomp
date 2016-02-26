<?php
App::uses('AppController', 'Controller');
/**
 * Items Controller
 *
 * @property Item $Item
 */
class ItemsController extends AppController {

	public $components = array('JsonTree');

	public function add($competence_id = null){

        if($this->Auth->user('role') !== 'admin'){
            $this->Session->setFlash(__('L\'ajout d\'item provenant des IO ne peut être effectué que par l\'administrateur.'), 'flash_success');
            $this->redirect(array('controller' => 'competences', 'action' => 'index', $competence_id));
        }

		$this->set('title_for_layout', __('Ajouter un item'));

		//On vérifie que le paramètre nommé competence_id a été fourni et qu'il existe.
		$competence_id = $this->CheckParams->checkForNamedParam('Competence','competence_id', $competence_id);

		$levels = $this->Item->Level->find('list', array('recursive' => 0));
		$this->set('levels', $levels);

		$this->set('path', $this->tabPathToString($this->Item->Competence->getPath($competence_id)));

		$this->JsonTree->passAllLpcnodesToView();

		if ($this->request->is('post')) {

			$this->Item->create();
			if ($this->Item->save($this->request->data)) {

				$this->Session->setFlash(__('L\'item a été correctement créé.'), 'flash_success');
				$this->redirect(array('controller' => 'items', 'action' => 'add', $competence_id));
			} else {
				$this->Session->setFlash(__('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.'), 'flash_error');
			}
		}
	}

    private function tabPathToString($path){
        $mypath = '';
        foreach($path as $competence){
            $mypath .= $competence['Competence']['title'].' <i class="fa fa-chevron-right"></i> ';
        }
        $mypath = substr($mypath, 0, -36);

        return $mypath;
    }

	public function edit($id = null, $classroom_id){
		$this->Item->id = $id;
		if (!$this->Item->exists()) {
			throw new NotFoundException(__('The item_id provided does not exist !'));
		}

		$item = $this->Item->findById($id);
		if (!$this->request->data) {
			$this->request->data = $item;
		}

		if($this->Auth->user('id') != $item['Item']['user_id'] && $this->Auth->user('role') != 'admin'){
			$this->Session->setFlash(__('Vous ne pouvez pas éditer un item dont vous n\'êtes pas propriétaire.'), 'flash_error');
			$this->redirect(array(
				'controller'    => 'evaluationsItems',
				'action'        => 'useditems', $classroom_id));
		}

		if ($this->request->is('post')) {
			if ($this->Item->save($this->request->data)) {
				$this->Session->setFlash(__('L\'item a été correctement modifée'), 'flash_success');
				if(isset($classroom_id)){
					$this->redirect(array(
						'controller'    => 'evaluationsItems',
						'action'        => 'useditems', $classroom_id));
				}else{
					$this->redirect(array(
						'controller'    => 'competences',
						'action'        => 'index'));
				}
			} else {
				$this->Session->setFlash(__('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.'), 'flash_error');
			}
		}

		//Récupération des ids des catégories existantes
		$competenceids = $this->Item->Competence->generateTreeList(null, null, null, "");
		$this->set('cid', $competenceids);

		$levels = $this->Item->Level->find('list', array('recursive' => 0));
		$this->set('levels', $levels);

		//Passage des compétences et du LPC
		$this->JsonTree->passAllCompetencesJsonTreeToView();
		$this->JsonTree->passAllLpcnodesToView();
	}

	public function editTitle($id = null){
		$this->Item->id = $id;
		if (!$this->Item->exists()) {
			throw new NotFoundException(__('The item_id provided does not exist !'));
		}
		
		//On vérifie qu'un paramètre nommé evaluation_id a été fourni et qu'il existe.
		if(isset($this->request->data['Item']['evaluation_id'])) {
       		$evaluation_id = intval($this->request->data['Item']['evaluation_id']);
       		$this->set('evaluation_id', $evaluation_id);
       		
       		$this->loadModel('Evaluation');
       		$this->Evaluation->id = $evaluation_id;
			if (!$this->Evaluation->exists()) {
				throw new NotFoundException(__('The evaluation_id provided does not exist !'));
			}
		} else {
			throw new NotFoundException(__('You must provide a evaluation_id in order to edit an item !'));
		}
						
		if ($this->request->is('post')) {	
		
			$this->Item->recursive = false;
			$item = $this->Item->read(null, $id);

			if($this->Auth->user('id') != $item['Item']['user_id'] && $this->Auth->user('role') != 'admin'){
				$this->Session->setFlash(__('Vous ne pouvez pas éditer un item dont vous n\'êtes pas propriétaire.'), 'flash_error');
				$this->redirect(array(
				    'controller'    => 'evaluations',
				    'action'        => 'attacheditems', $evaluation_id));
			}else{
				$this->Item->set('title', h($this->request->data['Item']['title']));					
				$this->Item->save(null, false);	
				
				$this->Session->setFlash(__('Le libellé de l\'item a été correctement mis à jour.'), 'flash_success');
				$this->redirect(array(
				    'controller'    => 'evaluations',
				    'action'        => 'attacheditems', $evaluation_id));

			}		
		}
		
	}
}
