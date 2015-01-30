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
	
	public function viewbul($id = null){	
		$this->set('title_for_layout', __('Visualiser une classe'));
		
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
		
		$classroom = $this->Report->Classroom->find('first', array(
			'conditions' => array('Classroom.id' => $report['Classroom']['id'])
		));		
		$this->set('classroom', $classroom);

		$ReqPupils = $this->Result->find('all', array(
			'fields' => array('pupil_id'),
			'order' => array('name', 'first_name'),
			'conditions' => array(
				'Evaluation.period_id' => $report['Report']['period_id'],
				'Evaluation.classroom_id' => $report['Classroom']['id']
			),
			'contain' => array(
				'Pupil.id',
				'Evaluation.Period.id',
				'Evaluation.Classroom.id'
			)
		));

		foreach($ReqPupils as $pupils){
			$pup[] = $pupils['Pupil']['id'];
		}

        if(!isset($pup)){
            $this->Session->setFlash(__('Aucun résultat saisi pour la/les période(s) configurée(s). Génération annulée !'), 'flash_error');
            $this->redirect(array('controller' => 'classrooms', 'action' => 'viewreports', $report['Classroom']['id']));
        }

		$pup = array_values(array_unique($pup));
		$nbpup = count($pup);
		foreach($pup as $ind => $id){
			$pourcent[$ind] = round((100 / $nbpup) * ($ind+1),1);
		}
		$tab = array('pupils' => $pup, 'pourcent' => $pourcent, 'report_id' => $report['Report']['id'], 'period_id' => implode(",",$report['Report']['period_id']), 'classroom_id' => $report['Classroom']['id']);
		$this->set('pupils', json_encode(json_encode($tab)));		
	}
	
	public function bul(){
	
		if(!isset($this->request->params['named']['output_type'])) {
       		throw new NotFoundException(__('You must provide an output type !')); 		
       	} else {
			$output_type = strval($this->request->params['named']['output_type']);
		}
		
		if(!isset($this->request->params['named']['pupil_id']) || !is_numeric($this->request->params['named']['pupil_id'])) {
       		throw new NotFoundException(__('You must provide pupil_id !')); 		
       	} else {
			$pupil =  intval($this->request->params['named']['pupil_id']);
		}
		
		if(!isset($this->request->params['named']['report_id']) || !is_numeric($this->request->params['named']['report_id'])) {
       		throw new NotFoundException(__('You must provide report_id !')); 		
       	} else {
			$report_id =  intval($this->request->params['named']['report_id']);
		}
		
		$this->set('pupil_id', $pupil);
		
		$this->loadModel('Report');
		$this->Report->id = $report_id;
		if(!$this->Report->exists()) {
			throw new NotFoundException(__('The report_id provided does not exist !'));
		}
		$report = $this->Report->find('first', array(
			'conditions' => array('Report.id' => $report_id)
		));
		
		$this->set('classroom_id', $report['Classroom']['id']);
		$this->set('period_id', implode(",",$report['Report']['period_id']));
		
		$this->set('report', $report);

		if ($output_type == 'pdf') {
			$this->set('output_type', 'pdf');
			$this->layout = 'pdf';
		}elseif ($output_type == 'html') {
			$this->layout = 'pdf';
		}else{
			throw new NotFoundException(__('Wrong output type !'));
		}
		
		$items = $this->Result->find('all', array(
			'fields' => array('result'),
			'conditions' => array(
				'Pupil.id' => $pupil,
				'Evaluation.period_id' => $report['Report']['period_id'],
				'Evaluation.classroom_id' => $report['Classroom']['id']
			),
			'contain' => array(
				'Item.title',
				'Item.competence_id',
				'Pupil.id',
				'Pupil.name',
				'Pupil.first_name',
				'Evaluation.Period.id',
				'Evaluation.Classroom.id'
			)
		));
	
		if(empty($items)){
			$this->Session->setFlash(__('Aucun résultat n\'a été saisi pour cet élève', 'flash_error'));
			$this->redirect(array('controller' => 'results', 'action' => 'bul'));
		}
			
		$this->set('items', $items);

        $competences = $this->Result->Item->Competence->findAllCompetencesFromCompetenceId($this->arrayValueRecursive('competence_id',$items),'!jstree');
        $this->set('competences', $competences);
	}

	function arrayValueRecursive($key, array $arr){
		$val = array();
		array_walk_recursive($arr, function($v, $k) use($key, &$val){
			if($k == $key) array_push($val, $v);
		});
		return count($val) > 1 ? $val : array_pop($val);
	}
	
	function concatenateReports(){
		$this->layout = 'ajax';
			
		$pdfMerged = new ZendPdf\PdfDocument();

		foreach($this->request->data['pupils'] as $pupil_id){
	      
	      // Load PDF Document
	      $OldPdf = ZendPdf\PdfDocument::load("files/".$this->request->data['classroom_id']."_".str_replace(',','',$this->request->data['period_id'])."_".$pupil_id.".pdf");
	
	      // Clone each page and add to merged PDF
	      $pages = count($OldPdf->pages);
	      for($i=0; $i<$pages; ++$i){
	         $page = clone $OldPdf->pages[$i];
	         $pdfMerged->pages[] = $page;
	      }
		}
	   
		// Save changes to PDF
	   	$pdfMerged->save("files/".$this->request->data['classroom_id']."_".str_replace(',','',$this->request->data['period_id']).".pdf");
		
		foreach($this->request->data['pupils'] as $pupil_id){
			unlink("files/".$this->request->data['classroom_id']."_".str_replace(',','',$this->request->data['period_id'])."_".$pupil_id.".pdf");
		}
	
	}
	
	function download(){
		$this->layout = 'ajax';
		if(!isset($this->request->params['named']['filename'])) {
       		throw new NotFoundException(__('You must provide a filename !')); 		
       	} else {
			$filename = strval($this->request->params['named']['filename']);
		}
		
		header('Content-disposition: attachment; filename='.$filename);
		header('Content-type: application/pdf');
		readfile('files/'.$filename);
		unlink('files/'.$filename);
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
