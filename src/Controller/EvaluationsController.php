<?php
namespace app\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
/**
 * Evaluations Controller
 *
 * @property Evaluations $Evaluations
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
            $evaluation = $this->Evaluations->get($this->request->params['pass'][0]);

            //L'administrateur a toujours accès
            if($user['role'] === 'admin'){
                return true;
            }else{
                //Classe pour lesquelles l'utilisateur courant est titulaire
                $classrooms_manager = $this->request->session()->read('Authorized')['classrooms_manager'];

                //Si l'utilisateur est propriétaire de l'évaluation ou s'il est titulaire de la classe
                //dans laquelle l'évaluation a été créé, alors on donne l'autorisation d'accès.
                if( ($evaluation->user_id == $user['id']) ||
                    in_array($evaluation->classroom_id,$classrooms_manager) ){
                    return true;
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

		$evaluation = $this->Evaluations->get($id, [
            'contain' => [
                'Users', 'Periods', 'Classrooms', 'Pupils',
                'Items' => function ($q) {
                    return $q
                        ->order(['EvaluationsItems.position' => 'ASC']);
                },
            ]
        ]);
		$this->set('evaluation', $evaluation);
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

        $classroom = $this->Evaluations->Classrooms->get($this->request->query['classroom_id'], ['contain' => 'Establishments']);
        $evaluation = $this->Evaluations->newEntity();

		if ($this->request->is('post')) {
            $evaluation = $this->Evaluations->newEntity($this->request->data);
            $evaluation->classroom_id = $classroom->id;
			if ($this->Evaluations->save($evaluation)) {
				$this->Flash->success('La nouvelle évaluation a été correctement ajoutée.');
				$this->redirect(array('controller' => 'evaluations','action' => 'attacheditems', $evaluation->id));
			} else {
				$this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
			}
		}

		$users = $this->Evaluations->Users->find('list', [
			    'conditions' => [
                    'id' => $this->Evaluations->Users->findAllUsersInClassroom($classroom->id)
                ]
        ]);

        $current_period = $classroom->establishment->period_id;

        $settingsTable = TableRegistry::get('Settings');
        $currentYear = $settingsTable->find('all', array('conditions' => array('Settings.key' => 'currentYear')))->first();

		$periods = $this->Evaluations->Periods->find('list', array(
			'conditions' => array(
                'establishment_id' => $classroom->establishment_id,
                'year_id' => $currentYear->value,
            )
        ));

		$pupils = $this->Evaluations->findPupilsByLevelsInClassroom($classroom->id);
		$this->set(compact('evaluation', 'classroom', 'users', 'periods', 'pupils', 'current_period'));
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

        $evaluation = $this->Evaluations->get($id, ['contain' => ['Pupils','Classrooms']]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $evaluation = $this->Evaluations->patchEntity($evaluation, $this->request->data);

            if ($this->Evaluations->save($evaluation)) {
                $this->Flash->success('L\'évaluation a été correctement modifiée.');
                $this->redirect(array('controller' => 'evaluations','action' => 'attacheditems', $evaluation->id));
            } else {
                $this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
            }
        }

        $users = $this->Evaluations->Users->find('list', [
            'conditions' => [
                'id' => $this->Evaluations->Users->findAllUsersInClassroom($evaluation->classroom_id)
            ]
        ]);

        $settingsTable = TableRegistry::get('Settings');
        $currentYear = $settingsTable->find('all', array('conditions' => array('Settings.key' => 'currentYear')))->first();

        $periods = $this->Evaluations->Periods->find('list', array(
            'conditions' => array(
                'establishment_id' => $evaluation->classroom->establishment_id,
                'year_id' => $currentYear->value,
            )
        ));

        $pupils = $this->Evaluations->findPupilsByLevelsInClassroom($evaluation->classroom_id);
        $this->set(compact('evaluation', 'users', 'periods', 'pupils', 'current_period'));
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
