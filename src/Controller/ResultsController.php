<?php
namespace app\Controller;

use App\Controller\AppController;

/**
 * Results Controller
 *
 * @property Result $Result
 */
class ResultsController extends AppController {

	public $helpers = array('ReportFormater');

	public function selectpupil(){
		//On vérifie qu'un paramètre nommé evaluation_id a été fourni et qu'il existe.
        $evaluation_id = $this->CheckParams->checkForNamedParam('Evaluation','evaluation_id', $this->request->params['named']['evaluation_id']);

		if ($this->request->is('post')) {
			$pupil_id = intval($this->request->data['Result']['pupil_id']);
			$this->Result->Pupil->id = $pupil_id;
			if (!$this->Result->Pupil->exists()) {
				$this->Flash->error('Le code barre élève que vous avez flashé est inconnu !');
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

	public function add(){
		//On vérifie qu'un paramètre nommé evaluation_id a été fourni et qu'il existe.
        $evaluation_id = $this->CheckParams->checkForNamedParam('Evaluation','evaluation_id', $this->request->params['named']['evaluation_id']);

        if(isset($this->request->params['named']['manual']) && $this->request->params['named']['manual'] == 'true')
            $this->set('manual', 'manual');

        $pupil_id = $this->CheckParams->checkForNamedParam('Pupil','pupil_id', $this->request->params['named']['pupil_id']);

		$hasItems = $this->Result->Evaluation->EvaluationsItem->find('all', array(
	        'conditions' => array('evaluation_id' => $evaluation_id),
	        'recursive' => 0
	    ));
	    if(empty($hasItems)){
		    $this->Flash->error('Impossible de saisir des résultats, aucun item associé à cette évaluation !');
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
			foreach($this->request->data['Results'] as $key => $value){
				if(isset($value) && !empty($value)){
					$data[$iteration]['Result']['pupil_id'] = $pupil_id;
					$data[$iteration]['Result']['evaluation_id'] = $evaluation_id;
					$data[$iteration]['Result']['item_id'] = $key;
					$data[$iteration]['Result']['result'] = $value;
					$data = $this->setResult($data, $iteration, $value);
					$iteration++;
				}
			}

			$this->Result->create();
			$this->Result->saveMany($data, array('validate' => 'all'));

			if(count($this->Result->invalidFields()) == 0){
				$this->Flash->success('Les résultats de <code>'.$pupil['Pupil']['first_name'].' '.$pupil['Pupil']['name'].'</code> pour l\'évaluation <code>'.$items[0]['Evaluation']['title'].'</code> ont bien été enregistrés.');
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
				$this->Flash->error('Les résultats de <code>'.$pupil['Pupil']['first_name'].' '.$pupil['Pupil']['name'].'</code> pour l\'évaluation <code>'.$items[0]['Evaluation']['title'].'</code> n\'ont pas pu être  enregistrés car votre saisie est incorrecte.<br />De nouveau, saisissez les résultats de l\'élève.');
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
			'fields' => array('sum_grade_a', 'sum_grade_b', 'sum_grade_c', 'sum_grade_d'),
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