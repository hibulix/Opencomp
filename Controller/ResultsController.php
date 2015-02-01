<?php
App::uses('AppController', 'Controller');

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
			$i=0;
			foreach($this->request->data['Results'] as $k => $v){
				if(isset($v) && !empty($v)){
					$data[$i]['Result']['pupil_id'] = $pupil_id;
					$data[$i]['Result']['evaluation_id'] = $evaluation_id;
					$data[$i]['Result']['item_id'] = $k;
					$data[$i]['Result']['result'] = $v;

					$i++;
				}
			}

			$this->Result->create();
			$this->Result->saveMany($data, array('validate' => 'all'));

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
			'fields' => array('result'),
			'order' => array('Pupil.name', 'Pupil.first_name'),
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

		foreach($results as $result){
			$stats[$result['Pupil']['id']]['first_name'] = $result['Pupil']['first_name'];
			$stats[$result['Pupil']['id']]['name'] = $result['Pupil']['name'];

			switch($result['Result']['result']){
				case 'A':
			        if(isset($stats[$result['Pupil']['id']]['numberA']))
			        	$stats[$result['Pupil']['id']]['numberA'] += 1;
			        else
			        	$stats[$result['Pupil']['id']]['numberA'] = 1;
			        break;
			    case 'B':
			        if(isset($stats[$result['Pupil']['id']]['numberB']))
			        	$stats[$result['Pupil']['id']]['numberB'] += 1;
			        else
			        	$stats[$result['Pupil']['id']]['numberB'] = 1;
			        break;
			    case 'C':
			        if(isset($stats[$result['Pupil']['id']]['numberC']))
			        	$stats[$result['Pupil']['id']]['numberC'] += 1;
			        else
			        	$stats[$result['Pupil']['id']]['numberC'] = 1;
			        break;
			    case 'D':
			        if(isset($stats[$result['Pupil']['id']]['numberD']))
			        	$stats[$result['Pupil']['id']]['numberD'] += 1;
			        else
			        	$stats[$result['Pupil']['id']]['numberD'] = 1;
			        break;
			}
		}

		foreach($stats as $id_pupil => $stat){

			if(!isset($stats[$id_pupil]['numberA'])) $stats[$id_pupil]['numberA'] = 0;
			if(!isset($stats[$id_pupil]['numberB'])) $stats[$id_pupil]['numberB'] = 0;
			if(!isset($stats[$id_pupil]['numberC'])) $stats[$id_pupil]['numberC'] = 0;
			if(!isset($stats[$id_pupil]['numberD'])) $stats[$id_pupil]['numberD'] = 0;

			$stats[$id_pupil]['totalresults'] = $stats[$id_pupil]['numberA'] + $stats[$id_pupil]['numberB'] + $stats[$id_pupil]['numberC'] + $stats[$id_pupil]['numberD'];

			$stats[$id_pupil]['percentA'] = $stats[$id_pupil]['numberA'] * 100 / $stats[$id_pupil]['totalresults'];
			$stats[$id_pupil]['percentB'] = $stats[$id_pupil]['numberB'] * 100 / $stats[$id_pupil]['totalresults'];
			$stats[$id_pupil]['percentC'] = $stats[$id_pupil]['numberC'] * 100 / $stats[$id_pupil]['totalresults'];
			$stats[$id_pupil]['percentD'] = $stats[$id_pupil]['numberD'] * 100 / $stats[$id_pupil]['totalresults'];
		}

		$this->set('results', $stats);

	}

}
