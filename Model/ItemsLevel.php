<?php
App::uses('AppModel', 'Model');
/**
 * ItemsLevel Model
 *
 * @property Item $Item
 * @property Level $Level
 */
class ItemsLevel extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'item_id',
		),
		'Level' => array(
			'className' => 'Level',
			'foreignKey' => 'level_id',
		)
	);
}
