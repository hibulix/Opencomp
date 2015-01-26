<?php
App::uses('AppModel', 'Model');
/**
 * Competence Model
 *
 * @property Competence $ParentCompetence
 * @property Competence $ChildCompetence
 * @property Item $Item
 */
class Competence extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';
	public $actsAs = array('CustomTree', 'Containable');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ParentCompetence' => array(
			'className' => 'Competence',
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
		'ChildCompetence' => array(
			'className' => 'Competence',
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
		),
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'competence_id',
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

    public function findAllCompetencesWithParentId($id){
        return $this->find('all',
            array(
                'contain' => array('ChildCompetence.id', 'Item.user_id = '.AuthComponent::user('id').' OR Item.type = 1 OR Item.type = 2'),
                'conditions' => array('Competence.parent_id'=>$id),
                'order' => 'Competence.lft ASC',
            )
        );
    }

	public function findAllCompetencesIn($ids_array = null){

		if(isset($ids_array) && is_array($ids_array))
			$conditions['Competence.id'] = $ids_array;
		else
			$conditions = array();

		$competences = $this->find('all',[
			'conditions' => [
				$conditions
			],
			'order' => ['Competence.lft'],
			'recursive' => -1
		]);

		$tab = array();

		foreach($competences as $num=>$c){
			$tab[$num]['id'] = $c['Competence']['id'];
			if($c['Competence']['parent_id'])
				$tab[$num]['parent'] = $c['Competence']['parent_id'];
			else
				$tab[$num]['parent'] = "#";
			$tab[$num]['icon'] = 'fa fa-lg fa-cubes';
			$tab[$num]['text'] = $c['Competence']['title'];
			$tab[$num]['data']['type'] = "noeud";
			$tab[$num]['li_attr']['data-type'] = "noeud";
			$tab[$num]['li_attr']['data-id'] = $c['Competence']['id'];
		}

		return $tab;
	}

}
