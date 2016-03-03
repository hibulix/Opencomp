<?php
App::uses('AppController', 'Controller');

/**
 * Results Controller
 *
 * @property Result $Result
 */
class ResultsController extends AppController {

	public $helpers = array('ReportFormater');

	public function setresultforspecificitem($evaluationid = null, $itemid = null, $result = null){
		$this->layout = 'ajax';

		try{
			//Existance et permissions
			if(!$this->Result->Evaluation->exists($evaluationid))
				throw new NotFoundException('Impossible de trouver cette évaluation !');
			if(!$this->Result->Evaluation->connectedUserIsOwnerOrAdmin($evaluationid))
				throw new UnauthorizedException('Vous n\'avez pas la permission d\'effectuer cette action !');
			if(!$this->Result->Evaluation->itemBelongsToEvaluation($evaluationid, $itemid))
				throw new NotFoundException('L\'item spécifié en paramètre n\'est pas associé à cette évaluation !');
			if(!in_array($result, ['A', 'B', 'C', 'D', 'NE']))
				throw new BadRequestException('Le résultat renseigné est invalide.');


			$this->Result->deleteAll(['evaluation_id' => $evaluationid, 'item_id' => $itemid]);
			$pupils = $this->Result->Evaluation->EvaluationsPupil->find('list',[
				'fields' => ['pupil_id', 'pupil_id'],
				'conditions'=>['evaluation_id' => $evaluationid]
			]);

			$iteration=0;
			foreach($pupils as $pupilid){
				$result_record = [];
				$data[$iteration]['Result']['evaluation_id'] = $evaluationid;
				$data[$iteration]['Result']['pupil_id'] = $pupilid;
				$data[$iteration]['Result']['item_id'] = $itemid;
				$data[$iteration]['Result']['result'] = $result;
				$data = $this->setResult($data, $iteration, $result);
				$iteration++;
			}
			$this->Result->saveMany($data, ['atomic' => true]);

			$this->response->statusCode(201);
			$this->response->type('application/json');
			$this->response->body(json_encode(['error' => false, 'message' => 'results created'],JSON_PRETTY_PRINT));
			return $this->response;
		}catch(Exception $e){
			$this->response->statusCode(500);
			$this->response->type('application/json');
			$this->response->body(json_encode(['error' => true, 'message' => $e->getMessage()],JSON_PRETTY_PRINT));
			return $this->response;
		}
	}

	public function selectpupil(){
		//On vérifie qu'un paramètre nommé evaluation_id a été fourni et qu'il existe.
        $evaluation_id = $this->CheckParams->checkForNamedParam('Evaluation','evaluation_id', $this->request->params['named']['evaluation_id']);

		if ($this->request->is('post')) {
			$pupil_id = intval($this->request->data['Result']['pupil_id']);
			$this->Result->Pupil->id = $pupil_id;
			if (!$this->Result->Pupil->exists()) {
				$this->Session->setFlash(__('Le code barre élève que vous avez flashé est inconnu !'), 'flash_error');
			} else {
				$this->redirect(array(
                    'action' => 'add',
                    'evaluation_id' => $evaluation_id,
                    'pupil_id' => $pupil_id
                ));
			}
		}
	}

    public function selectpupilmanual(){
        //On vérifie qu'un paramètre nommé evaluation_id a été fourni et qu'il existe.
        $evaluation_id = $this->CheckParams->checkForNamedParam('Evaluation','evaluation_id', $this->request->params['named']['evaluation_id']);

        if ($this->request->is('post')) {
            $pupil_id = intval($this->request->data['Result']['pupil_id']);
            $this->redirect(array(
                'action' => 'add',
                'evaluation_id' => $evaluation_id,
                'pupil_id' => $pupil_id,'manual' => 'true'
            ));
        }

        //On récupère le champs virtuel de Pupil et on le redéclare dans EvaluationsPupil
        $this->Result->Evaluation->EvaluationsPupil->virtualFields['wellnamed'] = $this->Result->Evaluation->EvaluationsPupil->Pupil->virtualFields['wellnamed'];

        $pupils = $this->Result->Evaluation->EvaluationsPupil->find('list', array(
            'fields' => array('EvaluationsPupil.pupil_id', 'wellnamed'),
            'conditions' => array('evaluation_id =' => $evaluation_id),
            'recursive' => 0
        ));

        $this->set('pupils', $pupils);
    }

	public function add_manual($evaluation_id = null){
		$evaluation = $this->Result->Evaluation->find('first', ['conditions' => ['Evaluation.id' => $evaluation_id]]);
		$pupils = $this->Result->Evaluation->findPupilsByLevelsInEvaluation($evaluation['Evaluation']['id']);
		$items = $this->Result->Evaluation->findItemsByPosition($evaluation_id);
		$results = $this->Result->find('all', [
			'conditions' => [
				'evaluation_id' =>$evaluation_id
			],
			'recursive' => -1
		]);
		$json_results = [];
		foreach($results as $num => $result){
			$json_results[$num]['item_id'] = $result['Result']['item_id'];
			$json_results[$num]['pupil_id'] = $result['Result']['pupil_id'];
			$json_results[$num]['result'] = $result['Result']['result'];
		}
		$json_results = json_encode($json_results);

		$this->set(compact('pupils', 'items', 'evaluation', 'json_results'));
	}

	public function add(){
		//On vérifie qu'un paramètre nommé evaluation_id a été fourni et qu'il existe.
        $evaluation_id = $this->CheckParams->checkForNamedParam('Evaluation','evaluation_id', $this->request->params['named']['evaluation_id']);

        if(isset($this->request->params['named']['manual']) && $this->request->params['named']['manual'] == 'true')
            $this->set('manual', 'manual');

        $pupil_id = $this->CheckParams->checkForNamedParam('Pupil','pupil_id', $this->request->params['named']['pupil_id']);

		$pupilHasTakenEvaluation = $this->Result->Evaluation->EvaluationsPupil->find('first', array(
            'conditions' => array(
                'evaluation_id' => $evaluation_id,
                'pupil_id' => $this->request->params['named']['pupil_id']
            ),
            'recursive' => 0
        ));

        if(empty($pupilHasTakenEvaluation)){
            $this->Session->setFlash(__('Impossible de saisir des résultats, cet élève n\'est pas associé à cette évaluation !'), 'flash_error');
            $this->redirect(array('controller' => 'evaluations', 'action' => 'manageresults', $evaluation_id));
        }

		$hasItems = $this->Result->Evaluation->EvaluationsItem->find('all', array(
	        'conditions' => array('evaluation_id' => $evaluation_id),
	        'recursive' => 0
	    ));
	    if(empty($hasItems)){
		    $this->Session->setFlash(__('Impossible de saisir des résultats, aucun item associé à cette évaluation !'), 'flash_error');
		    $this->redirect(array('controller' => 'evaluations', 'action' => 'attacheditems', $evaluation_id));
	    }

		$items = $this->Result->Evaluation->findItemsByPosition($evaluation_id);

		//Récupération des résultats éventuels
		$qresults = $this->Result->findAllByEvaluationIdAndPupilId($evaluation_id, $pupil_id, array('Result.item_id', 'Result.result'));
		if(!empty($qresults)){
			foreach($qresults as $result){
					$results[$result['Result']['item_id']] = $result['Result']['result'];
			}
		}else{
			$results = array();
		}

	    $this->set('items', $items);
	    $this->set('results', $results);

	    $pupil = $this->Result->Pupil->find('first', array(
	        'conditions' => array('id' => $pupil_id),
	        'recursive' => -1
	    ));
	    $this->set('pupil', $pupil);

		if ($this->request->is('post')) {
			$iteration=0;
			$delete = array();

			foreach($this->request->data['Results'] as $key => $value){
				if(isset($value) && !empty($value)){
					$data[$iteration]['Result']['pupil_id'] = $pupil_id;
					$data[$iteration]['Result']['evaluation_id'] = $evaluation_id;
					$data[$iteration]['Result']['item_id'] = $key;
					$data[$iteration]['Result']['result'] = $value;
					$data = $this->setResult($data, $iteration, $value);
					$iteration++;
				}else{
					$delete[] = $key;
				}
			}

			if(isset($data)){
				$this->Result->create();
				$this->Result->saveMany($data, array('validate' => 'all'));
			}

			if(count($delete) > 0){
				foreach ($delete as $id) {
					$this->Result->deleteAll(array('Result.pupil_id' => $pupil_id, 'Result.evaluation_id' => $evaluation_id, 'item_id' => $id), false);
				}
			}

			if(count($this->Result->invalidFields()) == 0){
				$this->Session->setFlash(__('Les résultats de <code>'.$pupil['Pupil']['first_name'].' '.$pupil['Pupil']['name'].'</code> pour l\'évaluation <code>'.$items[0]['Evaluation']['title'].'</code> ont bien été enregistrés.'), 'flash_success');
                if(isset($this->request->params['named']['manual']) && $this->request->params['named']['manual'] == 'true') {
                    $this->redirect(array(
                            'controller'    => 'results',
                            'action'        => 'selectpupilmanual',
                            'evaluation_id' => $evaluation_id)
                    );
                }else{
                    $this->redirect(array(
                        'controller'    => 'results',
                        'action'        => 'selectpupil',
                        'evaluation_id' => $evaluation_id)
                    );
                }
			}else{
				$this->Session->setFlash(__('Les résultats de <code>'.$pupil['Pupil']['first_name'].' '.$pupil['Pupil']['name'].'</code> pour l\'évaluation <code>'.$items[0]['Evaluation']['title'].'</code> n\'ont pas pu être  enregistrés car votre saisie est incorrecte.<br />De nouveau, saisissez les résultats de l\'élève.'), 'flash_error');
			}
		}
	}

	private function setResult($data, $iteration, $grade){
		switch($grade){
			case 'A':
				$data[$iteration]['Result']['grade_a'] = 1;
				break;
			case 'B':
				$data[$iteration]['Result']['grade_b'] = 1;
				break;
			case 'C':
				$data[$iteration]['Result']['grade_c'] = 1;
				break;
			case 'D':
				$data[$iteration]['Result']['grade_d'] = 1;
				break;
		}
		return $data;
	}

	public function analyseresults($id = null) {
		//On charge le modèle report et on récupère les infos du bulletin à générer.
		$this->loadModel('Report');
		$this->Report->id = $id;
		if (!$this->Report->exists()) {
			throw new NotFoundException(__('The report_id provided does not exist !'));
		}
		$report = $this->Report->find('first', array(
			'conditions' => array('Report.id' => $id)
		));
		$this->set('report', $report);

		$results = $this->Result->find('all', array(
			'fields' => array('SUM(grade_a)', 'SUM(grade_b)', 'SUM(grade_c)', 'SUM(grade_d)'),
			'order' => array('Pupil.name', 'Pupil.first_name'),
			'group' => array('Pupil.id'),
			'conditions' => array(
				'Evaluation.period_id' => $report['Report']['period_id'],
				'Evaluation.classroom_id' => $report['Classroom']['id']
			),
			'contain' => array(
				'Evaluation',
				'Pupil.id',
				'Pupil.first_name',
				'Pupil.name'
			)
		));

		$this->set('results', $results);

	}

}
