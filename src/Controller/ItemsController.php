<?php
namespace app\Controller;

use App\Controller\AppController;
/**
 * Items Controller
 *
 * @property Item $Item
 */
class ItemsController extends AppController {

	public $components = array('JsonTree');

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
			$this->Flash->error('Vous ne pouvez pas éditer un item dont vous n\'êtes pas propriétaire.');
			$this->redirect(array(
				'controller'    => 'evaluationsItems',
				'action'        => 'useditems', $classroom_id));
		}

		if ($this->request->is('post')) {
			if ($this->Item->save($this->request->data)) {
				$this->Flash->success('L\'item a été correctement modifée');
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
				$this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
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
				$this->Flash->error('Vous ne pouvez pas éditer un item dont vous n\'êtes pas propriétaire.');
				$this->redirect(array(
				    'controller'    => 'evaluations',
				    'action'        => 'attacheditems', $evaluation_id));
			}else{
				$this->Item->set('title', h($this->request->data['Item']['title']));					
				$this->Item->save(null, false);	
				
				$this->Flash->success('Le libellé de l\'item a été correctement mis à jour.');
				$this->redirect(array(
				    'controller'    => 'evaluations',
				    'action'        => 'attacheditems', $evaluation_id));

			}		
		}
		
	}
}
