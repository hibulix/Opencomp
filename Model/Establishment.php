<?php
App::uses('AppModel', 'Model');
/**
 * Establishment Model
 *
 * @property User $User
 * @property Academy $Academy
 * @property Classroom $Classroom
 * @property Period $Period
 */
class Establishment extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
    public $actsAs = array('Containable');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'address' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'postcode' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'town' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'academy_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Academy' => array(
			'className' => 'Academy',
			'foreignKey' => 'academy_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Classroom' => array(
			'className' => 'Classroom',
			'foreignKey' => 'establishment_id',
			'dependent' => false,
		),
		'Period' => array(
			'className' => 'Period',
			'foreignKey' => 'establishment_id',
			'dependent' => false,
		)
	);

}
