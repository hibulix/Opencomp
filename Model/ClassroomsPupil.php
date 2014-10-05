<?php
App::uses('AppModel', 'Model');
/**
 * ClassroomsPupil Model
 *
 * @property Classroom $Classroom
 * @property Pupil $Pupil
 * @property Level $Level
 */
class ClassroomsPupil extends AppModel {

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	public $actsAs = array('Containable');
	
/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Classroom' => array(
			'className' => 'Classroom',
			'foreignKey' => 'classroom_id',
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
		'Level' => array(
			'className' => 'Level',
			'foreignKey' => 'level_id',
			'conditions' => '',
			'fields' => 'title',
			'order' => ''
		)
	);
}
