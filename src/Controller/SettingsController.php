<?php
namespace app\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Datasource\ConnectionManager;
use Cake\Filesystem\Folder;
use ReflectionClass;
use ReflectionMethod;

/**
 * Settings Controller
 *
 * @property Setting $Setting
 */
class SettingsController extends AppController {

	public function isAuthorized($user = null) {
		if (in_array($this->action, array('index'))) {
			if($user['role'] === 'admin')
				return true;
			else
				return false;
		}else{
			return true;
		}
	}

	public function index(){
	
		$this->loadModel('Years');
		$years = $this->Years->find('list');
		$this->set('years', $years);

        Configure::config('default', new PhpConfig());
        Configure::load('opencomp', 'default');
		$currentYear = Configure::read('Opencomp.currentYear');
	    $lastYear = Configure::read('Opencomp.lastYear');
	    $yubikeyClientId = Configure::read('Opencomp.yubikeyClientId');
	    $yubikeySecretKey = Configure::read('Opencomp.yubikeySecretKey');

        if ($this->request->is('post')) {

            Configure::config('default', new PhpConfig());
            Configure::write([
                'Opencomp.currentYear' => $this->request->data['Setting']['currentYear'],
                'Opencomp.lastYear' => $this->request->data['Setting']['lastYear'],
                'Opencomp.yubikeyClientId' => $this->request->data['Setting']['yubikeyClientId'],
                'Opencomp.yubikeySecretKey' => $this->request->data['Setting']['yubikeySecretKey']
            ]);
            Configure::dump('opencomp', 'default', ['Opencomp']);

        }

        $this->set(compact('currentYear', 'lastYear', 'yubikeyClientId', 'yubikeySecretKey'));
	}

    public function extractMethods(){

        $saved_metas = $this->Settings->getMetadatas();

        $controllers_dir = new Folder('../src/Controller');
        $controllers = $controllers_dir->find('.*Controller\.php');

        if(($key = array_search('AppController.php', $controllers)) !== false) {
            unset($controllers[$key]);
        }

        $actions = [];
        foreach($controllers as $controller){
            require_once($controller);
            $classname = 'app\Controller\\'.substr($controller,0,-4);
            $object = new $classname;
            $class = new ReflectionClass($object);

            $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);

            foreach($methods as $method){
                if(!in_array($method->name,['isAuthorized','beforeFilter','login','logout','generationProgressWidget','needYubikeyToken','setAuthorizedClassroomsId'],true))
                    $actions[substr($method->class, 15, -10)][] = $method->name;
            }
        }
        unset($actions['App']);
        unset($actions['Pages']);
        unset($actions['\\']);

        $this->set(compact('actions', 'saved_metas'));

        if ($this->request->is('post')) {
            $connection = ConnectionManager::get('default');
            foreach($this->request->data as $controller => $methods){
                foreach($methods as $method => $label){
                    $label = $connection->quote($label);
                    if($method === 'ControllerName' && !empty($label)){
                        $connection->execute("INSERT INTO metadatas (controller,value)
                                              VALUES ('$controller',$label)
                                              ON DUPLICATE KEY UPDATE value=$label;");
                    }elseif(!empty($label)){
                        $connection->execute("INSERT INTO metadatas (controller,action,value)
                                              VALUES ('$controller','$method',$label)
                                              ON DUPLICATE KEY UPDATE value=$label;");
                    }
                }
            }
            $this->redirect(['action' => 'extractmethods']);
        }
    }
}
