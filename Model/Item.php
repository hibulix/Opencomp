<?php
App::uses('AppModel', 'Model');
/**
 * Item Model
 *
 * @property Competence $Competence
 * @property User $User
 * @property Classroom $Classroom
 * @property Result $Result
 * @property Evaluation $Evaluation
 * @property Level $Level
 */
class Item extends AppModel {

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
				'message' => 'Vous devez renseigner ce champ !',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'Level' => array(
				'rule' => array('multiple', array(
		            'min' => 1
		        )),
		        'message' => 'Merci de choisir une, deux ou trois options'
		),
		'competence_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'place' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'type' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'classroom_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Competence' => array(
			'className' => 'Competence',
			'foreignKey' => 'competence_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Classroom' => array(
			'className' => 'Classroom',
			'foreignKey' => 'classroom_id',
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
		'Result' => array(
			'className' => 'Result',
			'foreignKey' => 'item_id',
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


/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Evaluation' => array(
			'className' => 'Evaluation',
			'joinTable' => 'evaluations_items',
			'foreignKey' => 'item_id',
			'associationForeignKey' => 'evaluation_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		),
		'Level' => array(
			'className' => 'Level',
			'joinTable' => 'items_levels',
			'foreignKey' => 'item_id',
			'associationForeignKey' => 'level_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);

    function findItemsWithCompetenceId($id){
        return $this->find('all',
            array(
                'contain' => array ('Level.title'),
                'fields' => array('id', 'title', 'type', 'lpcnode_id'),
                'conditions' => array(
                    'Item.competence_id'=>$id,
                    'OR' => array(
                        'Item.user_id' => AuthComponent::user('id'),
                        'Item.type IN' => array('1','2'),
                    ),
                ),
            )
        );

    }

	function findAllItems(){
		$this->contain('Level');
		$items = $this->find('all',[
			'conditions' => [
				'OR' => [
					'Item.user_id' => AuthComponent::user('id'),
					'Item.type IN' => ['1','2'],
				]
			]
		]);

		$tab = array();
		$num = 0;

		foreach($items as $item){
			$tab[$num]['id'] = 'item-'.$item['Item']['id'];
			$tab[$num]['parent'] = $item['Item']['competence_id'];
			$tab[$num]['icon'] = 'fa fa-lg fa-cube ' . $this->returnItemClassType($item);
			$tab[$num]['text'] =  $this->returnLpcLink($item) . $this->returnFormattedLevelsItem($item) . $item['Item']['title'];
			$tab[$num]['data']['type'] = "feuille";
			$tab[$num]['data']['id'] = $item['Item']['id'];
			$tab[$num]['li_attr']['data-type'] = "feuille";
			$tab[$num]['li_attr']['data-id'] = $item['Item']['id'];
			$num++;
		}

		return $tab;
	}

	private function returnFormattedLevelsItem($item){
		$string = '';
		foreach($item['Level'] as $level){
			$string .= sprintf('<span class="label label-default">%s</span> ',$level['title']);
		}
		return $string;
	}

	private function returnLpcLink($item){
		if(isset($item['Item']['lpcnode_id'])){
			return '<span class="info label label-success"><i class="fa fa-link"></i></span> ';
		}else{
			return '<span class="info label label-danger"><i class="fa fa-unlink"></i></span> ';
		}
	}

	private function returnItemClassType($item){
		switch ($item['Item']['type']) {
			case 1:
				return "text-danger";
				break;
			case 2:
				return "text-info";
				break;
			case 3:
				return "text-success";
				break;
		}
	}
	
	function beforeValidate($options = array()) {
	  if (!isset($this->data['Level']['Level'])
	  || empty($this->data['Level']['Level'])) {
	    $this->invalidate('Level'); // fake validation error on Item
	    $this->Level->invalidate('Level', 'Sélectionnez au moins un niveau !');
	  }
	  return true;
	}

}
