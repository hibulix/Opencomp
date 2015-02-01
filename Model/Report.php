<?php
App::uses('AppModel', 'Model');
/**
 * Result Model
 *
 * @property Evaluation $Evaluation
 * @property Pupil $Pupil
 * @property Item $Item
 */
class Report extends AppModel {

	public $actsAs = array('Containable');

	public $validate = array(
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Vous devez compléter ce champ.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'period_id' => array(
			'multiple' => array(
		        'rule' => array('multiple', array(
		            'min' => 1
		        )),
		        //'message' => '<i class="icon-user"></i> Vous devez séléctionner au moins une période !'
		    )
		),
		'header' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Vous devez compléter ce champ.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'footer' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Vous devez compléter ce champ.',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	
	public $belongsTo = array(
			'Classroom' => array(
			'className' => 'Classroom',
			'foreignKey' => 'classroom_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
	
	

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	
	public function beforeSave($options = array()){
		if(isset($this->data['Report']['period_id']))
			$this->data['Report']['period_id'] = implode(",",$this->data['Report']['period_id']);
		if(isset($this->data['Report']['page_break']) && is_array($this->data['Report']['page_break']))
			$this->data['Report']['page_break'] = implode(",",$this->data['Report']['page_break']);

		return true;
	}
	
	public function afterFind($results, $primary = false){
        if(isset($results[0]['Report']['period_id']))
            $results[0]['Report']['period_id'] = explode(",",$results[0]['Report']['period_id']);
		if(isset($results[0]['Report']['page_break']))
        $results[0]['Report']['page_break'] = explode(",",$results[0]['Report']['page_break']);
		
		return $results;
	}
}
