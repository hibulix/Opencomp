<?php
App::uses('AppModel', 'Model');
/**
 * Classroom Model
 *
 * @property User $User
 * @property Year $Year
 * @property Establishment $Establishment
 * @property CompetencesUser $CompetencesUser
 * @property Evaluation $Evaluation
 * @property Item $Item
 * @property Pupil $Pupil
 * @property User $User
 */
class Classroom extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';
	public $actsAs = array('Containable');
	
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
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'year_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'establishment_id' => array(
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
		),
		'Year' => array(
			'className' => 'Year',
			'foreignKey' => 'year_id',
		),
		'Establishment' => array(
			'className' => 'Establishment',
			'foreignKey' => 'establishment_id',
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'CompetencesUser' => array(
			'className' => 'CompetencesUser',
			'foreignKey' => 'classroom_id',
			'dependent' => false,
		),
		'Evaluation' => array(
			'className' => 'Evaluation',
			'foreignKey' => 'classroom_id',
			'dependent' => false,
		),
		'Report' => array(
			'className' => 'Report',
			'foreignKey' => 'classroom_id',
			'dependent' => false,
		),
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'classroom_id',
			'dependent' => false,
		)
	);


/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Pupil' => array(
			'className' => 'Pupil',
			'joinTable' => 'classrooms_pupils',
			'foreignKey' => 'classroom_id',
			'associationForeignKey' => 'pupil_id',
			'unique' => 'keepExisting',
		),
		'User' => array(
			'className' => 'User',
			'joinTable' => 'classrooms_users',
			'foreignKey' => 'classroom_id',
			'associationForeignKey' => 'user_id',
			'unique' => 'keepExisting',
		)
	);

}
