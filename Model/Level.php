<?php
App::uses('AppModel', 'Model');
/**
 * Level Model
 *
 * @property Cycle $Cycle
 * @property ClassroomsPupil $ClassroomsPupil
 * @property Item $Item
 */
class Level extends AppModel {

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
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'cycle_id' => array(
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
		'Cycle' => array(
			'className' => 'Cycle',
			'foreignKey' => 'cycle_id',
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
		'ClassroomsPupil' => array(
			'className' => 'ClassroomsPupil',
			'foreignKey' => 'level_id',
			'dependent' => false,
		)
	);


/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Item' => array(
			'className' => 'Item',
			'joinTable' => 'items_levels',
			'foreignKey' => 'level_id',
			'associationForeignKey' => 'item_id',
			'unique' => 'keepExisting',
		)
	);

}
