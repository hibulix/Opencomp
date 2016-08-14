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
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\I18n\I18n;
use Cake\I18n\Time;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 * @property \App\Model\Table\ClassroomsTable Classrooms
 * @property \App\Model\Table\EstablishmentsTable Establishments
 * @property \App\Model\Table\SettingsTable Settings
 * @property \App\Model\Table\TownsTable Towns
 */
class AppController extends Controller
{
    public $helpers = [
        'Url',
        'Utils'
    ];

    /**
     * @return void
     */
    public function initialize()
    {
        $this->loadComponent('Flash');
        $this->loadComponent('RequestHandler');
        $this->loadComponent('CakeDC/Users.UsersAuth');
        if (($this->request->is('json') || $this->request->is('xml')) && !$this->Auth->user('id')) {
            $this->Auth->config('storage', 'Memory');
            $this->Auth->config('unauthorizedRedirect', false);
            $this->Auth->config('checkAuthIn', 'Controller.initialize');
            $this->Auth->config('loginAction', false);
            $this->Auth->config('authenticate', null);
            $this->Auth->config('authenticate', ['ApiKey' => [
                'require_ssl' => false,
            ]]);
        }
        I18n::locale('fr_FR');
        Time::setDefaultLocale('fr-FR');
    }

    /**
     * @param Event $event Cake event
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        $this->set('title_for_layout', 'Opencomp');
        $this->set('params', $this->request->params);
    }
}
