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

	public $virtualFields = array(
		'sum_grade_a' => 'SUM(Result.grade_a)',
		'sum_grade_b' => 'SUM(Result.grade_b)',
		'sum_grade_c' => 'SUM(Result.grade_c)',
		'sum_grade_d' => 'SUM(Result.grade_d)'
	);

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
			'fields' => array('result'),
			'conditions' => array(
				'Pupil.id' => $pupil_id,
				'Evaluation.period_id' => $period_id,
				'Evaluation.classroom_id' => $classroom_id
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
	
	public function beforeSave($options = array()){
		$evaluation_id = $this->data['Result']['evaluation_id'];
		$pupil_id = $this->data['Result']['pupil_id'];
		$item_id = $this->data['Result']['item_id'];
		
		$this->deleteAll(array('Result.evaluation_id' => $evaluation_id, 'Result.pupil_id' => $pupil_id, 'Result.item_id' => $item_id), false);
		
		return true;
	}
}
