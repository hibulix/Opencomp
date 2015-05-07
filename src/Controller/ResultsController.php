<?php
namespace app\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Results Controller
 *
 * @property bool|object Results
 */
class ResultsController extends AppController {

	public $helpers = array('ReportFormater');

	public function selectpupil(){
        $evaluation = $this->Results->Evaluations->get($this->request->query['evaluation_id']);

		if ($this->request->is('post')) {
			$pupil_id = intval($this->request->data['pupil_id']);
			$this->Results->Pupils->get($pupil_id);

			$this->redirect(array(
				'action' => 'add',
				'evaluation_id' => $evaluation->id,
				'pupil_id' => $pupil_id
			));
		}

		$this->set(compact('evaluation'));
	}

    public function selectpupilmanual(){
        $evaluation = $this->Results->Evaluations->get($this->request->query['evaluation_id']);

        if ($this->request->is('post')) {
            $pupil_id = intval($this->request->data['pupil_id']);
            $this->redirect(array(
                'action' => 'add',
                'evaluation_id' => $evaluation->id,
                'pupil_id' => $pupil_id,'manual' => 'true'
            ));
        }

		$this->EvaluationsPupils = TableRegistry::get('EvaluationsPupils');
        $pupils = $this->EvaluationsPupils->find();
        $pupils = $this->EvaluationsPupils->find('list', array(
            'fields' => [
                'full_name' => $pupils->func()->concat([
                    'first_name' => 'literal',
                    ' ',
                    'name' => 'literal',
                ]),
                'pupil_id'
            ],
			'keyField' => 'pupil_id',
			'valueField' => 'full_name',
            'conditions' => array('evaluation_id =' => $evaluation->id)
        ))->contain(['Pupils'])->hydrate(false)->toArray();

		$this->set('pupil', $this->Results->Pupils->newEntity());
		$this->set('evaluation', $evaluation);
        $this->set('pupils', $pupils);
    }

	public function add(){
		$evaluation = $this->Results->Evaluations->get($this->request->query['evaluation_id']);
		$pupil = $this->Results->Pupils->get($this->request->query['pupil_id']);

        if(isset($this->request->query['manual']) && $this->request->query['manual'] == 'true')
            $this->set('manual', 'manual');

		$this->EvaluationsItems = TableRegistry::get('EvaluationsItems');
		$hasItems = $this->EvaluationsItems->find('all', array(
	        'conditions' => array('evaluation_id' => $evaluation->id),
	        'recursive' => 0
	    ))->count();
	    if(!$hasItems){
		    $this->Flash->error('Impossible de saisir des résultats, aucun item associé à cette évaluation !');
		    $this->redirect(array('controller' => 'evaluations', 'action' => 'attacheditems', $evaluation->id));
	    }

		$items = $this->Results->Evaluations->findItemsByPosition($evaluation->id);

		//Récupération des résultats éventuels
		$results = $this->Results->newEntity();
		$saved_results = $this->Results->find('list', [
			'conditions' => [
				'evaluation_id' => $evaluation->id,
				'pupil_id' => $pupil->id
			],
			'keyField' => 'item_id',
			'valueField' => 'result',
		]);
		$saved_results = $saved_results->hydrate(false)->toArray();
	    $this->set(compact('items','results','pupil', 'evaluation', 'saved_results'));

		if ($this->request->is(['post', 'patch', 'put'])) {
			$iteration=0; $data = [];
			foreach($this->request->data as $key => $value){
				if(isset($value) && !empty($value)){
					$data[$iteration]['pupil_id'] = $pupil->id;
					$data[$iteration]['evaluation_id'] = $evaluation->id;
					$data[$iteration]['item_id'] = $key;
					$data[$iteration]['result'] = $value;
					$data = $this->setResult($data, $iteration, $value);
					$iteration++;
				}
			}

			$results = $this->Results->newEntities($data);
			$resultTable = $this->Results;

			if(count($results)){
				$transaction = $resultTable->connection()->transactional(function () use ($resultTable, $results) {
					$resultTable->deleteAll(['pupil_id' => $results[0]->pupil_id, 'evaluation_id' => $results[0]->evaluation_id]);
					foreach ($results as $entity) {
						$resultTable->save($entity, ['atomic' => false]);
					}
				});
			}else{
				$resultTable->deleteAll(['pupil_id' => $pupil->id, 'evaluation_id' => $evaluation->id]);
			}

			if(!isset($transaction) || $transaction !== false){
				$this->Flash->success('Les résultats de <code>'.$pupil->full_name.'</code> pour l\'évaluation <code>'.$evaluation->title.'</code> ont bien été enregistrés.');
                if(isset($this->request->query['manual']) && $this->request->query['manual'] == 'true') {
                    $this->redirect(array(
                            'controller'    => 'results',
                            'action'        => 'selectpupilmanual',
                            'evaluation_id' => $evaluation->id)
                    );
                }else{
                    $this->redirect(array(
                        'controller'    => 'results',
                        'action'        => 'selectpupil',
                        'evaluation_id' => $evaluation->id)
                    );
                }
			}else{
				$this->Flash->error('Les résultats de <code>'.$pupil->full_name.'</code> pour l\'évaluation <code>'.$evaluation->title.'</code> n\'ont pas pu être  enregistrés car votre saisie est incorrecte.<br />De nouveau, saisissez les résultats de l\'élève.');
			}
		}
	}

	private function setResult($data, $iteration, $grade){
		switch($grade){
			case 'A':
				$data[$iteration]['grade_a'] = 1;
				break;
			case 'B':
				$data[$iteration]['grade_b'] = 1;
				break;
			case 'C':
				$data[$iteration]['grade_c'] = 1;
				break;
			case 'D':
				$data[$iteration]['grade_d'] = 1;
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
