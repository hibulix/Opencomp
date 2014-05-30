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

}
