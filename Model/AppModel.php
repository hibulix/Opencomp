<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 *
 * @method findById() findById($id)
 */
class AppModel extends Model {

    public function find($conditions = NULL, $fields = array (), $order = NULL, $recursive = NULL) 
	{ 
        if ($conditions == 'list' && is_array($this->displayField)) 
            return Set::combine($this->find('all', $fields, $order, $recursive), "{n}.{$this->name}.{$this->primaryKey}", $this->displayField); 
        else 
            return parent::find($conditions, $fields, $order, $recursive); 
    }

}
