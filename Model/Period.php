<?php
App::uses('AppModel', 'Model');
/**
 * Period Model
 *
 * @property Year $Year
 * @property Establishment $Establishment
 * @property Evaluation $Evaluation
 */
class Period extends AppModel {

/**
 * Display field
 *
 * @var string
 */	
	public $virtualFields = array(
    	'wellnamed' => 'CONCAT("du ",DATE_FORMAT(Period.begin, "%e/%m/%Y"), " au ", DATE_FORMAT(Period.end, "%e/%m/%Y"))'
    );
    
    public $displayField = 'wellnamed';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'begin' => array(
			'date' => array(
				'rule' => array('date'),
			),
		),
		'end' => array(
			'date' => array(
				'rule' => array('date'),
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
		'Evaluation' => array(
			'className' => 'Evaluation',
			'foreignKey' => 'period_id',
			'dependent' => false,
		)
	);

}
