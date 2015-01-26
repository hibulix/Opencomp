<?php
App::uses('AppModel', 'Model');
/**
 * Lpcnode Model
 *
 * @property Lpcnode $ParentLpcnode
 * @property Item $Item
 * @property Lpcnode $ChildLpcnode
 */
class Lpcnode extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';
	public $actsAs = array('Tree', 'Containable');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'lft' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'rght' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ParentLpcnode' => array(
			'className' => 'Lpcnode',
			'foreignKey' => 'parent_id',
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
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'lpcnode_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'ChildLpcnode' => array(
			'className' => 'Lpcnode',
			'foreignKey' => 'parent_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

    public function findAllNodesWithParentId($id){
        return $this->find('all',
            array(
                'contain' => array('ChildLpcnode.id', 'Item.type = 1'),
                'conditions' => array('Lpcnode.parent_id'=>$id),
                'order' => 'Lpcnode.lft ASC',
            )
        );
    }

	public function findAllLpcnodeIn($ids_array = null){

		if(isset($ids_array) && is_array($ids_array))
			$conditions['Lpcnode.id'] = $ids_array;
		else
			$conditions = array();

		$this->contain('ChildLpcnode');
		$lpcnodes = $this->find('all',[
			'conditions' => [
				$conditions
			],
			'order' => ['Lpcnode.lft'],
			'recursive' => -1
		]);

		$tab = array();

		foreach($lpcnodes as $num=>$node){
			$tab[$num]['id'] = $node['Lpcnode']['id'];
			if($node['Lpcnode']['parent_id'])
				$tab[$num]['parent'] = $node['Lpcnode']['parent_id'];
			else
				$tab[$num]['parent'] = "#";
			$tab[$num]['text'] = $node['Lpcnode']['title'];
			$tab[$num]['li_attr']['data-id'] = $node['Lpcnode']['id'];

			if(count($node['ChildLpcnode'])){
				$tab[$num]['icon'] = 'fa fa-lg fa-cubes';
				$tab[$num]['data']['type'] = "noeud";
				$tab[$num]['li_attr']['data-type'] = "noeud";
			}else{
				$tab[$num]['icon'] = 'fa fa-lg fa-cube text-danger';
				$tab[$num]['data']['type'] = "feuille";
				$tab[$num]['li_attr']['data-type'] = "feuille";
			}
		}

		return $tab;
	}
}
