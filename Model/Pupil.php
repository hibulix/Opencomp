<?php
App::uses('AppModel', 'Model');
/**
 * Pupil Model
 *
 * @property Tutor $Tutor
 * @property Level $Level
 * @property Result $Result
 * @property Classroom $Classroom
 */
class Pupil extends AppModel {

/**
 * Display field
 *
 * @var string
 */
    public $virtualFields = array(
        'wellnamed' => 'CONCAT(Pupil.first_name, " ", Pupil.name)'
    );
    public $displayField = 'wellnamed';

	public $recursive = 2;

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'minlength' => array(
				'rule' => array('notBlank'),
			),
		),
		'first_name' => array(
			'minlength' => array(
				'rule' => array('notBlank'),
			),
		),
		'sex' => array(
			'inlist' => array(
				'rule' => array('notBlank'),
			),
		),
		'birthday' => array(
			'date' => array(
				'rule' => array('date'),
			),
		),
		'state' => array(
			'boolean' => array(
				'rule' => array('boolean'),
			),
		),
		'tutor_id' => array(
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
		'Tutor' => array(
			'className' => 'Tutor',
			'foreignKey' => 'tutor_id',
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
			'foreignKey' => 'pupil_id',
			'dependent' => false,
		),
		'ClassroomsPupil' => array(
			'className' => 'ClassroomsPupil',
			'foreignKey' => 'pupil_id',
			'dependent' => false,
		),
		'EvaluationsPupil' => array(
			'className' => 'EvaluationsPupil',
			'foreignKey' => 'pupil_id',
			'dependent' => false,
		),
	);

	public function isUploadedFile($params) {
		if ((isset($params['error']) && $params['error'] == 0) ||
			(!empty( $params['tmp_name']) && $params['tmp_name'] != 'none')
		) {
			return is_uploaded_file($params['tmp_name']);
		}
		return false;
	}
}
