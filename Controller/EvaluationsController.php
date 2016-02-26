<?php
App::uses('AppController', 'Controller');
/**
 * Evaluations Controller
 *
 * @property Evaluation $Evaluation
 */
class EvaluationsController extends AppController {

    /**
     * Fonction permettant de déterminer les droits d'accès à une évaluation
     *
     * @param null $user
     * @return bool
     */
    public function isAuthorized($user = null) {
        if(isset($this->request['pass'][0])){
            //$this->request['pass'][0] correspond a l'id de l'évaluation passé
            $this->Evaluation->id = $this->request['pass'][0];

            //Vérification de l'existance de l'évaluation avant de continuer
            if ($this->Evaluation->exists()) {
                //L'administrateur a toujours accès
                if($user['role'] === 'admin'){
                    return true;
                }else{
                    //On désactive la récupération des enregistrements associés
                    //et on charge les informations de l'évaluation courante.
                    $this->Evaluation->recursive = -1;
                    $current_record = $this->Evaluation->read(array('classroom_id','user_id'));

                    //Classe pour lesquelles l'utilisateur courant est titulaire
                    $classrooms_manager = $this->Session->read('Authorized')['classrooms_manager'];

                    //Si l'utilisateur est propriétaire de l'évaluation ou s'il est titulaire de la classe
                    //dans laquelle l'évaluation a été créé, alors on donne l'autorisation d'accès.
                    if( ($current_record['Evaluation']['user_id'] == $user['id']) ||
                        in_array($current_record['Evaluation']['classroom_id'],$classrooms_manager) ){
                        return true;
                    }
                }
            }
        }else{
            //Si on a fourni le paramètre classroom_id
            if(isset($this->request->params['named']['classroom_id'])) {
                if($user['role'] === 'admin'){
                    return true;
                }
                $classroom_id = intval($this->request->params['named']['classroom_id']);
                $this->Evaluation->Classroom->id = $classroom_id;
                $classrooms = $this->Session->read('Authorized')['classrooms'];
                if ($this->Evaluation->Classroom->exists() && in_array($classroom_id,$classrooms)) {
                    return true;
                }
            }
        }
        return false;
    }

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function attacheditems($id = null) {
        $this->set('title_for_layout', __('Détails d\'une évaluation'));

		$this->Evaluation->id = $id;
		$this->Evaluation->contain(array('User', 'Period', 'Classroom', 'Pupil.first_name', 'Pupil.name'));
		$evaluation = $this->Evaluation->findById($id);		
		$this->set('evaluation', $evaluation);
		
		$this->Evaluation->EvaluationsItem->contain(array('Item.title', 'Item.type'));
		$items = $this->Evaluation->EvaluationsItem->findAllByEvaluationId($id, array(), array('EvaluationsItem.position' => 'asc'));
		$this->set('items', $items);
		
	}
	
	public function manageresults($id = null) {
		$this->Evaluation->id = $id;
		$this->Evaluation->contain(array('User', 'Period', 'Classroom', 'Pupil.Result.evaluation_id = '.$id, 'Item'));
		$evaluation = $this->Evaluation->findById($id);
		$this->set('evaluation', $evaluation);
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
        $this->set('title_for_layout', __('Ajouter une évaluation'));

        $classroom_id = intval($this->request->params['named']['classroom_id']);
        $this->Evaluation->Classroom->id = $classroom_id;
        $this->set('classroom_id', intval($classroom_id));

		if(isset($this->request->params['named']['evaluation_id'])){
			$evaluation = $this->checkEvaluationId($this->request->params['named']['evaluation_id'], $classroom_id);
			$this->set('evaluation', $evaluation);
		}

		if ($this->request->is('post')) {
			$this->Evaluation->create();
			if ($this->Evaluation->save($this->request->data)) {
				if(isset($evaluation)){
					$data = [];
					foreach($evaluation['EvaluationsItem'] as $num => $item){
						$data[$num]['evaluation_id'] = $this->Evaluation->getLastInsertID();
						$data[$num]['item_id'] = $item['item_id'];
						$data[$num]['position'] = $item['position'];
					}
					$this->Evaluation->EvaluationsItem->saveAll($data);
				}
				$this->Session->setFlash(__('La nouvelle évaluation a été correctement ajoutée.'), 'flash_success');
				$this->redirect(array('controller' => 'evaluations','action' => 'attacheditems', $this->Evaluation->id));
			} else {
				$this->Session->setFlash(__('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.'), 'flash_error');
			}
		}
		
		$users = $this->Evaluation->User->find('list', array(
			'recursive' => 0,
			'conditions' => array('id' => $this->Evaluation->User->findAllUsersInClassroom($classroom_id))
			)
		);
		
		$etab = $this->Evaluation->Classroom->find('first', array(
			'fields'=>'establishment_id',
			'conditions'=>array(
				'Classroom.id'=>$classroom_id
			),
			'recursive'=>-1
		));
		
		$this->Evaluation->Classroom->contain(array('Establishment.current_period_id'));
		$current_period = $this->Evaluation->Classroom->findById($classroom_id, 'Establishment.current_period_id');
		$current_period = $current_period['Establishment']['current_period_id'];

        $this->loadModel('Setting');
        $currentYear = $this->Setting->find('first', array('conditions' => array('Setting.key' => 'currentYear')));
		
		$periods = $this->Evaluation->Period->find('list', array(
			'conditions' => array(
                'establishment_id' => $etab['Classroom']['establishment_id'],
                'year_id' => $currentYear['Setting']['value'],
            ),
			'recursive' => 0));

		if(empty($periods)){
			$this->Session->setFlash(__('Votre directeur doit saisir les périodes de l\'établissement avant que vous ne puissiez créer une évaluation'), 'flash_error');
			$this->redirect(array('controller' => 'classrooms','action' => 'viewtests', $classroom_id));
		}
		
		$pupils = $this->Evaluation->findPupilsByLevelsInClassroom($classroom_id);

		if(!isset($pupils)){
			$this->Session->setFlash(__('Vous devez saisir les élèves de votre classe avant de pouvoir créer une évaluation'), 'flash_error');
			$this->redirect(array('controller' => 'classrooms','action' => 'view', $classroom_id));
		}

		$this->set(compact('classrooms', 'users', 'periods', 'pupils', 'current_period'));
	}

	private function checkEvaluationId($id, $classroom_id){
		$evaluation_id = intval($id);
		$evaluation = $this->Evaluation->find('first',['conditions' => ['Evaluation.id' => $evaluation_id], 'contain' => ['EvaluationsItem']]);
		if(isset($evaluation) && $evaluation['Evaluation']['user_id'] == $this->Auth->user('id')){
			return $evaluation;
		}else{
			$this->Session->setFlash(__('Vous devez avoir créé l\'évaluation pour créer une copie.'), 'flash_error');
			$this->redirect(array('controller' => 'classrooms','action' => 'viewtests', $classroom_id));
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
        $this->set('title_for_layout', __('Modifier une évaluation'));

		$this->Evaluation->id = $id;
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Evaluation->save($this->request->data)) {
				$this->Session->setFlash(__('L\'évaluation a été correctement mise à jour.'), 'flash_success');
				$this->redirect(array('controller' => 'evaluations','action' => 'attacheditems', $id));
			} else {
				$classroom_id = $this->request->data['Evaluation']['classroom_id'];
				$this->set('evaluation_id', $id);
				$this->set('classroom_id', $classroom_id);
				$this->Session->setFlash(__('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.'), 'flash_error');
			}
		} else {
			$this->request->data = $this->Evaluation->read(null, $id);
			$classroom_id = $this->request->data['Evaluation']['classroom_id'];
			$this->set('evaluation_id', $id);
			$this->set('classroom_id', $classroom_id);
		}
	
		$users = $this->Evaluation->User->find('list', array(
			'recursive' => 0,
			'conditions' => array('id' => $this->Evaluation->User->findAllUsersInClassroom($classroom_id))
			)
		);
		
		$etab = $this->Evaluation->Classroom->find('first', array(
			'fields'=>'establishment_id',
			'conditions'=>array(
				'Classroom.id'=>$classroom_id
			),
			'recursive'=>-1
		));
		
		$periods = $this->Evaluation->Period->find('list', array(
			'conditions' => array('establishment_id' => $etab['Classroom']['establishment_id']),
			'recursive' => 0));

		$pupils = $this->Evaluation->findPupilsByLevelsInClassroom($classroom_id);
		$this->set(compact('classrooms', 'users', 'periods', 'pupils'));
	}

	public function analyseresults($id = null){
		$id = (intval($id));
		$this->Evaluation->id = $id;
		$this->Evaluation->contain(array('User', 'Period', 'Classroom', 'Pupil.Result.evaluation_id = '.$id, 'Item', 'Result'));
		$evaluation = $this->Evaluation->findById($id);

		$itemx = '';
		foreach($evaluation['Item'] as $item){
			$itemno = $item['EvaluationsItem']['position'];
			$itemx .= "'item $itemno', ";
		}

		$item_division = $this->Evaluation->Result->findItemDivision($id);
		$global_results = $this->Evaluation->Result->globalResults($id);

		$this->set(compact('evaluation', 'global_results'));
		$this->set('x',substr($itemx, 0, -2));
		$this->set('y',$item_division);
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
		$this->Evaluation->id = $id;
		$classroom_id = $this->Evaluation->read('Evaluation.classroom_id', $id);
		if ($this->Evaluation->delete()) {
			$this->Session->setFlash(__("L'évaluation a été correctement supprimée"), 'flash_success');
			$this->redirect(array(
			    'controller'    => 'classrooms',
			    'action'        => 'viewtests',
			    $classroom_id['Evaluation']['classroom_id']));
		}
		$this->Session->setFlash(__("L'évaluation n'a pas pu être supprimée en raison d'une erreur interne"), 'flash_error');
		$this->redirect(array(
		    'controller'    => 'classrooms',
		    'action'        => 'viewtests'));
	}
}
