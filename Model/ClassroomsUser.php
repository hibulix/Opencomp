<?php
App::uses('AppModel', 'Model');
/**
 * ClassroomsUser Model
 *
 * @property User $User
 * @property Classroom $Classroom
 */
class ClassroomsUser extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
		),
		'Classroom' => array(
			'className' => 'Classroom',
			'foreignKey' => 'classroom_id',
		)
	);
}
