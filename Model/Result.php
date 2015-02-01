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
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'pupil_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'item_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'result' => array(
			'inlist' => array(
				'rule' => array('inlist', array('A', 'B', 'C', 'D', 'ABS', 'NE')),
				'message' => 'Your custom message here',
				'allowEmpty' => true,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Evaluation' => array(
			'className' => 'Evaluation',
			'foreignKey' => 'evaluation_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Pupil' => array(
			'className' => 'Pupil',
			'foreignKey' => 'pupil_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'item_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
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
