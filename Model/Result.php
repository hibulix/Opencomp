<?php
App::uses('AppModel', 'Model');
/**
 * Result Model
 *
 * @property Evaluation $Evaluation
 * @property Pupil $Pupil
 * @property Item $Item
 */
class Result extends AppModel {

	public $actsAs = array('Containable');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'evaluation_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'pupil_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'item_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'result' => array(
			'inlist' => array(
				'rule' => array('inlist', array('A', 'B', 'C', 'D', 'ABS', 'NE')),
				'message' => 'Your custom message here',
				'allowEmpty' => true,
			),
		),
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Evaluation' => array(
			'className' => 'Evaluation',
			'foreignKey' => 'evaluation_id',
		),
		'Pupil' => array(
			'className' => 'Pupil',
			'foreignKey' => 'pupil_id',
		),
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'item_id',
		)
	);

	public function findResultsForReport($pupil_id, $classroom_id, $period_id){
		return $this->find('all', array(
			'fields' => array('result','Level.title'),
			'conditions' => array(
				'Pupil.id' => $pupil_id,
				'Evaluation.period_id' => $period_id,
				'Evaluation.classroom_id' => $classroom_id
			),
			'joins' => array(
				array(
					'table' => 'classrooms_pupils',
					'alias' => 'ClassroomsPupil',
					'type' => 'INNER',
					'conditions' => array(
							'ClassroomsPupil.pupil_id = Pupil.id',
							'ClassroomsPupil.classroom_id = Evaluation.classroom_id'
					)
				),
				array(
					'table' => 'levels',
					'alias' => 'Level',
					'type' => 'INNER',
					'conditions' => array(
							'ClassroomsPupil.level_id = Level.id'
					)
				)
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
	}

	/**
	 * Function used to save multiple results at once for all pupils
	 * associted with an evaluation_id for a specific item_id.
	 *
	 * @param int $evaluation_id evaluation_id related to the results
	 * @param int $item_id item_id related to the results
	 * @param string $result result to save : A, B, C, D, NE or ABS
	 *
	 * @return boolean whether the results has been saved or not
	 */
	public function saveGlobalResultEvaluationItem($evaluation_id, $item_id, $result){
		$this->deleteAll(['evaluation_id' => $evaluation_id, 'item_id' => $item_id]);
		$pupils = $this->Evaluation->EvaluationsPupil->find('list',[
			'fields' => ['pupil_id', 'pupil_id'],
			'conditions'=>['evaluation_id' => $evaluation_id]
		]);

		$iteration=0;
		foreach($pupils as $pupil_id){
			$data[$iteration]['Result']['evaluation_id'] = $evaluation_id;
			$data[$iteration]['Result']['pupil_id'] = $pupil_id;
			$data[$iteration]['Result']['item_id'] = $item_id;
			$data[$iteration]['Result']['result'] = $result;
			$data = $this->setResult($data, $iteration, $result);
			$iteration++;
		}
		return $this->saveMany($data, ['atomic' => true]);
	}

	/**
	 * Function used to save multiple results at once for all pupils
	 * associted with an evaluation_id for a specific item_id.
	 *
	 * @param int $evaluation_id evaluation_id related to the results
	 * @param int $item_id item_id related to the results
	 * @param mixed $parameter [description]
	 * @param string $result result to save : A, B, C, D, NE or ABS
	 *
	 * @return boolean whether the results has been saved or not
	 */
	public function saveGlobalResultEvaluationItemLevel($evaluation_id, $item_id, $level_id, $result){
		$evaluationPupils = $this->Evaluation->findPupilsByLevelsInEvaluation($evaluation_id);
		$targetedPupils = array_keys($evaluationPupils[intval($level_id)]['pupils']);
		$this->deleteAll(['evaluation_id' => $evaluation_id, 'item_id' => $item_id, 'pupil_id IN' => $targetedPupils]);

		$iteration=0;
		foreach($targetedPupils as $pupil_id){
			$data[$iteration]['Result']['evaluation_id'] = $evaluation_id;
			$data[$iteration]['Result']['pupil_id'] = $pupil_id;
			$data[$iteration]['Result']['item_id'] = $item_id;
			$data[$iteration]['Result']['result'] = $result;
			$data = $this->setResult($data, $iteration, $result);
			$iteration++;
		}
		return $this->saveMany($data, ['atomic' => true]);
	}

	public function saveResultEvaluationItemPupil($evaluation_id, $item_id, $pupil_id, $result){
		$this->deleteAll(['evaluation_id' => $evaluation_id, 'item_id' => $item_id, 'pupil_id' => $pupil_id]);
		$data[0]['Result']['evaluation_id'] = $evaluation_id;
		$data[0]['Result']['pupil_id'] = $pupil_id;
		$data[0]['Result']['item_id'] = $item_id;
		$data[0]['Result']['result'] = $result;
		$data = $this->setResult($data, 0, $result);
		return $this->saveMany($data, ['atomic' => true]);
	}

	public function saveGlobalResultEvaluationPupil($evaluation_id, $pupil_id, $result){
		$this->deleteAll(['evaluation_id' => $evaluation_id, 'pupil_id' => $pupil_id]);
		$evaluationItems = $this->Evaluation->findItemsByPosition($evaluation_id);

		$iteration=0;
		foreach($evaluationItems as $item){
			$data[$iteration]['Result']['evaluation_id'] = $evaluation_id;
			$data[$iteration]['Result']['pupil_id'] = $pupil_id;
			$data[$iteration]['Result']['item_id'] = $item['Item']['id'];
			$data[$iteration]['Result']['result'] = $result;
			$data = $this->setResult($data, $iteration, $result);
			$iteration++;
		}
		return $this->saveMany($data, ['atomic' => true]);
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

	public function findItemDivision($evaluation_id){
		$item_division = $this->Evaluation->query("
			SELECT GROUP_CONCAT(a) a, GROUP_CONCAT(b) b, GROUP_CONCAT(c) c, GROUP_CONCAT(d) d, GROUP_CONCAT(ne) ne, GROUP_CONCAT(abs) abs
			FROM
			(
				SELECT r1.item_id, SUM(COALESCE(`grade_a`,0)) as a, SUM(COALESCE(`grade_b`,0)) as b, SUM(COALESCE(`grade_c`,0)) as c, SUM(COALESCE(`grade_d`,0)) as d,
				(SELECT COUNT(result) FROM results WHERE evaluation_id = $evaluation_id AND `item_id` = r1.item_id AND result='NE') as ne,
				(SELECT COUNT(result) FROM results WHERE evaluation_id = $evaluation_id AND `item_id` = r1.item_id AND result='ABS') as abs
				FROM results r1
				INNER JOIN evaluations_items ON evaluations_items.item_id = r1.item_id AND evaluations_items.evaluation_id = r1.evaluation_id
				WHERE r1.evaluation_id = $evaluation_id
				GROUP BY r1.item_id, evaluations_items.position
				ORDER BY evaluations_items.position ASC
			) q
		");

		return $item_division[0][0];
	}

	public function globalResults($evaluation_id){
		$item_division = $this->Evaluation->query("
			SELECT GROUP_CONCAT(a) a, GROUP_CONCAT(b) b, GROUP_CONCAT(c) c, GROUP_CONCAT(d) d, GROUP_CONCAT(ne) ne, GROUP_CONCAT(abs) abs
			FROM
			(
				SELECT SUM(COALESCE(`grade_a`,0)) as a, SUM(COALESCE(`grade_b`,0)) as b, SUM(COALESCE(`grade_c`,0)) as c, SUM(COALESCE(`grade_d`,0)) as d,
				(SELECT COUNT(result) FROM results WHERE evaluation_id = $evaluation_id AND result='NE') as ne,
				(SELECT COUNT(result) FROM results WHERE evaluation_id = $evaluation_id AND result='ABS') as abs
				FROM results
				WHERE results.evaluation_id = $evaluation_id
			) q
		");

		return implode(', ',$item_division[0][0]);
	}

	public function beforeSave($options = array()){
		$evaluation_id = $this->data['Result']['evaluation_id'];
		$pupil_id = $this->data['Result']['pupil_id'];
		$item_id = $this->data['Result']['item_id'];

		$this->deleteAll(array('Result.evaluation_id' => $evaluation_id, 'Result.pupil_id' => $pupil_id, 'Result.item_id' => $item_id), false);

		return true;
	}
}
