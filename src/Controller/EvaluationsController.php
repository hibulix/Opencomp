<?php
namespace app\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
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

        if(isset($this->request->params['pass'][0])){
            //$this->request->params['pass'][0] correspond a l'id de l'évaluation passé
            $this->Evaluation->id = $this->request->params['pass'][0];

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
                    $classrooms_manager = $this->request->session()->read('Authorized')['classrooms_manager'];

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
            if(isset($this->request->query['classroom_id'])) {
                if($user['role'] === 'admin'){
                    return true;
                }
                $classroom_id = intval($this->request->query['classroom_id']);
                $classrooms = array_merge($this->request->session()->read('Authorized')['classrooms'], $this->request->session()->read('Authorized')['classrooms_manager']);
                if (in_array($classroom_id,$classrooms)) {
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
		$result = $this->Evaluation->resultsForAnEvaluation($id);
		$this->set('resultats', $result);
		$this->set('evaluation', $evaluation);
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
        $this->set('title_for_layout', __('Ajouter une évaluation'));

        $classroom_id = intval($this->request->query['classroom_id']);


		if ($this->request->is('post')) {
			$this->Evaluation->create();
			if ($this->Evaluation->save($this->request->data)) {
				$this->Flash->success('La nouvelle évaluation a été correctement ajoutée.');
				$this->redirect(array('controller' => 'evaluations','action' => 'attacheditems', $this->Evaluation->id));
			} else {
				$this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
			}
		}

		$users = $this->Evaluations->Users->find('list', array(
			'recursive' => 0,
			'conditions' => array('id' => $this->Evaluations->Users->findAllUsersInClassroom($classroom_id))
			)
		);

		$etab = $this->Evaluations->Classrooms->find('all', array(
			'fields'=>'establishment_id',
			'conditions'=>array(
				'Classrooms.id'=>$classroom_id
			),
		))->first();

        $classroom = $this->Evaluations->Classrooms->get($classroom_id,[
            'fields' => ['Establishments.current_period_id'],
            'contain' => ['Establishments']
        ]);

        $current_period = $classroom['Establishments']->current_period_id;

        $settingsTable = TableRegistry::get('Settings');
        $currentYear = $settingsTable->find('all', array('conditions' => array('Settings.key' => 'currentYear')))->first();

		$periods = $this->Evaluations->Periods->find('list', array(
			'conditions' => array(
                'establishment_id' => $etab->establishment_id,
                'year_id' => $currentYear->value,
            )
        ));

		$pupils = $this->Evaluations->findPupilsByLevelsInClassroom($classroom_id);
		$this->set(compact('classroom_id', 'classrooms', 'users', 'periods', 'pupils', 'current_period'));
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
				$this->Flash->success('L\'évaluation a été correctement mise à jour.');
				$this->redirect(array('controller' => 'evaluations','action' => 'attacheditems', $id));
			} else {
				$this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
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
			$this->Flash->success('L\'évaluation a été correctement supprimée');
			$this->redirect(array(
			    'controller'    => 'classrooms',
			    'action'        => 'viewtests',
			    $classroom_id['Evaluation']['classroom_id']));
		}
		$this->Flash->error('L\'évaluation n\'a pas pu être supprimée en raison d\'une erreur interne');
		$this->redirect(array(
		    'controller'    => 'classrooms',
		    'action'        => 'viewtests'));
	}
}
