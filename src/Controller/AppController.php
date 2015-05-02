<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
namespace app\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\I18n\Time;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    public $components = array(
        'Flash',
        'CheckParams',
    	'Auth' => array(
    		'authorize' => 'Controller'
    	)
    );
    public $helpers = array(
        'Url',
    	'Utils'
    );

    public function isAuthorized($user = null) {
        // Chacun des utilisateur enregistré peut accéder aux fonctions publiques
        if (empty($this->request->params['admin']))
       	{
            return true;
        }

        // Seulement les administrateurs peuvent accéder aux fonctions d'administration
        if (isset($this->request->params['admin'])) {
            return (bool)($user['role'] === 'admin');
        }

        // Par défaut n'autorise pas
        return false;
    }

    public function beforeFilter(Event $event){
        Time::$defaultLocale = 'fr-FR';
    }
}
