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
	
	public function beforeSave($options = array()){
		$evaluation_id = $this->data['Result']['evaluation_id'];
		$pupil_id = $this->data['Result']['pupil_id'];
		$item_id = $this->data['Result']['item_id'];
		
		$this->deleteAll(array('Result.evaluation_id' => $evaluation_id, 'Result.pupil_id' => $pupil_id, 'Result.item_id' => $item_id), false);
		
		return true;
	}
}
