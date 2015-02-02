<?php
App::uses('AppModel', 'Model');
/**
 * Year Model
 *
 * @property Classroom $Classroom
 * @property Period $Period
 */
class Year extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Classroom' => array(
			'className' => 'Classroom',
			'foreignKey' => 'year_id',
			'dependent' => false,
		),
		'Period' => array(
			'className' => 'Period',
			'foreignKey' => 'year_id',
			'dependent' => false,
		)
	);

}
