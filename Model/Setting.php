<?php
App::uses('AppModel', 'Model');
/**
 * Setting Model
 *
 */
class Setting extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'key' => array(
			'alphanumeric' => array(
				'rule' => array('alphanumeric'),
			),
		),
	);
}
