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

	public function findItemDivision($evaluation_id){
		$item_division = $this->Evaluation->query("
			SELECT GROUP_CONCAT(a) a, GROUP_CONCAT(b) b, GROUP_CONCAT(c) c, GROUP_CONCAT(d) d, GROUP_CONCAT(ne) ne, GROUP_CONCAT(abs) abs
			FROM
			(
				SELECT results.item_id as uid, SUM(COALESCE(`grade_a`,0)) as a, SUM(COALESCE(`grade_b`,0)) as b, SUM(COALESCE(`grade_c`,0)) as c, SUM(COALESCE(`grade_d`,0)) as d,
				(SELECT COUNT(result) FROM results WHERE evaluation_id = $evaluation_id AND `item_id` = uid AND result='NE') as ne,
				(SELECT COUNT(result) FROM results WHERE evaluation_id = $evaluation_id AND `item_id` = uid AND result='ABS') as abs
				FROM results
				INNER JOIN evaluations_items ON evaluations_items.item_id = results.item_id AND evaluations_items.evaluation_id = results.evaluation_id
				WHERE results.evaluation_id = $evaluation_id
				GROUP BY results.item_id
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
