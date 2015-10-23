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
			),
		),
		'place' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'type' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'classroom_id' => array(
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
		'Competence' => array(
			'className' => 'Competence',
			'foreignKey' => 'competence_id',
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
		),
		'Classroom' => array(
			'className' => 'Classroom',
			'foreignKey' => 'classroom_id',
		),
		'Lpcnode' => array(
			'className' => 'Lpcnode',
			'foreignKey' => 'lpcnode_id',
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
		),
		'Level' => array(
			'className' => 'Level',
			'joinTable' => 'items_levels',
			'foreignKey' => 'item_id',
			'associationForeignKey' => 'level_id',
			'unique' => 'keepExisting',
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

	function findCompetenceIdFromItemsIds($items_ids){
		return array_unique($this->find('list',[
			'fields' => ['competence_id','competence_id'],
			'conditions' => [
				'Item.id IN' => $items_ids,
				'Item.deleted' => false
			]
		]));
	}

	function findAllItems($item_ids = null)
	{
		$this->contain('Level');

		if (isset($item_ids)){
			$conditions['Item.id IN'] = $item_ids;
		}

		$conditions['OR']['Item.user_id'] = AuthComponent::user('id');
		$conditions['OR']['Item.type'] = '1';

		if(AuthComponent::user('role') !== 'admin')
			$conditions['AND']['Item.deleted'] = '0';

		$items = $this->find('all',[
			'conditions' => $conditions
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

			if($item['Item']['deleted']){
				$tab[$num]['a_attr']['style'] = 'color:#cacaca; text-decoration:line-through;';
				$tab[$num]['data']['deleted'] = "true";
			}

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
			return '<span title="Cet item est jumelé avec le LPC" class="info label label-success"><i class="fa fa-link"></i></span> ';
		}else{
			return '<span title="Cet item n\'est pas jumelé avec le LPC" class="info label label-danger"><i class="fa fa-unlink"></i></span> ';
		}
	}

	private function returnItemClassType($item){

		if($item['Item']['deleted'])
			return "";

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
