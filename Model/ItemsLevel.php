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

	public function findItemsIdsFromLevelIds($level_ids){
		if(count($level_ids) > 1){
			$conditions['level_id IN'] = $level_ids;
		}else{
			$conditions['level_id'] = $level_ids;
		}

		return array_unique($this->find('list',[
			'fields' => ['item_id','item_id'],
			'conditions' => $conditions
		]));
	}
}
