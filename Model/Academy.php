<?php
App::uses('AppModel', 'Model');

/**
  * Academy.php
  *
  * PHP version 5
  *
  * @category Model
  * @package  Opencomp
  * @author   Jean Traullé <jtraulle@gmail.com>
  * @license  http://www.opensource.org/licenses/agpl-v3 The Affero GNU General Public License
  * @link     http://www.opencomp.fr
  */

/**
 * Modèle de gestion des académies.
 *
 * @category Model
 * @package  Opencomp
 * @author   Jean Traullé <jtraulle@gmail.com>
 * @license  http://www.opensource.org/licenses/agpl-v3 The Affero GNU General Public License
 * @link     http://www.opencomp.fr
 */
 class Academy extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Vous devez renseigner ce champ obligatoire.'
			),
		),
		'type' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Establishment' => array(
			'className' => 'Establishment',
			'foreignKey' => 'academy_id',
			'dependent' => false,
		)
	);


/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'User' => array(
			'className' => 'User',
			'joinTable' => 'academies_users',
			'foreignKey' => 'academy_id',
			'associationForeignKey' => 'user_id',
			'unique' => 'keepExisting',
		)
	);

}
