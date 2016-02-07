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

App::uses('Controller', 'Controller');

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
    	'DebugKit.Toolbar', 
    	'Session',
        'CheckParams',
    	'Auth' => array(
    		'authorize' => 'Controller'
    	)
    );
    public $helpers = array(
    	'Utils',
        'Session',
        'Html' => array('className' => 'BoostCake.BoostCakeHtml'),
        'Form' => array('className' => 'BoostCake.BoostCakeForm'),
        'Paginator' => array('className' => 'BoostCake.BoostCakePaginator'),
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
    
    public function beforeFilter(){
    	$this->Auth->flash['element'] = "flash_error";
    	$this->Auth->authError = "Vous n'êtes pas autorisé à accéder à cette page !";
    }

    function beforeRender() {
        if($this->name == 'CakeError') {
            $this->layout = 'error';
        }

        if($this->Auth->user() && $this->request->params['action'] !== 'generationProgressWidget'){
            $this->logevent();
        }
    }

    private function logevent(){
        $browser = new WhichBrowser\Parser($this->getallheaders());
        $object_id = isset($this->request->params['pass'][0]) ? $this->request->params['pass'][0] : null;
        $parameters =  !empty($this->request->params['named']) ? json_encode($this->request->params['named'], JSON_PRETTY_PRINT) : null;

        $this->loadModel('Log');
        $this->Log->save(
            array(
                'user_id' => $this->Auth->user('id'),
                'remote_addr' => $this->request->clientIp(),
                'controller' => $this->request->params['controller'],
                'action' => $this->request->params['action'],
                'object_id' => $object_id,
                'parameters' => $parameters,
                'device_type' => $browser->device->type,
                'os_name' => $browser->os->name,
                'os_version' => isset($browser->os->version->value) ? $browser->os->version->value : null,
                'os_version_nickname' => isset($browser->os->version->nickname) ? $browser->os->version->nickname : null,
                'browser_name' => $browser->browser->name,
                'browser_version' => isset($browser->browser->version->value) ? $browser->browser->version->value : null,
                'browser_engine' => $browser->engine->name,
                'session_id' => Security::hash(session_id())
            )
        );
    }

    private function getallheaders()
    {
        if (!function_exists('getallheaders'))
        {
            $headers = '';
            foreach ($_SERVER as $name => $value)
            {
                if (substr($name, 0, 5) == 'HTTP_')
                {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
            return $headers;
        }else{
            return getallheaders();
        }
    }

}

