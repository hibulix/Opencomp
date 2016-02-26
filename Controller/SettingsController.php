<?php
App::uses('AppController', 'Controller');
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
	
		$this->loadModel('Year');	
		$years = $this->Year->find('list');
		$this->set('years', $years);
		
		$currentYear = $this->Setting->find('first', array('conditions' => array('Setting.key' => 'currentYear')));
	    $lastYear = $this->Setting->find('first', array('conditions' => array('Setting.key' => 'lastYear')));
	    $pathMysqldump = $this->Setting->find('first', array('conditions' => array('Setting.key' => 'pathMysqldump')));
	    $pathBackup = $this->Setting->find('first', array('conditions' => array('Setting.key' => 'pathBackup')));
	    $saveOnExit = $this->Setting->find('first', array('conditions' => array('Setting.key' => 'saveOnExit')));
	    $yubikeyClientId = $this->Setting->find('first', array('conditions' => array('Setting.key' => 'yubikeyClientID')));
	    $yubikeySecretKey = $this->Setting->find('first', array('conditions' => array('Setting.key' => 'yubikeySecretKey')));
	    
	    if(empty($currentYear))
			$this->set('currentYear', null);
		else
			$this->set('currentYear', $currentYear['Setting']['value']);
			
			
		if(empty($lastYear))
			$this->set('lastYear', null);
		else
			$this->set('lastYear', $lastYear['Setting']['value']);
			
		if(empty($saveOnExit))
			$this->set('saveOnExit', null);
		else
			$this->set('saveOnExit', $saveOnExit['Setting']['value']);
			
		if(empty($pathMysqldump))
			$this->set('pathMysqldump', null);
		else
			$this->set('pathMysqldump', $pathMysqldump['Setting']['value']);
		
		if(empty($pathBackup))
			$this->set('pathBackup', null);
		else
			$this->set('pathBackup', $pathBackup['Setting']['value']);
			
		if(empty($yubikeyClientId))
			$this->set('yubikeyClientId', null);
		else
			$this->set('yubikeyClientId', $yubikeyClientId['Setting']['value']);
			
		if(empty($yubikeySecretKey))
			$this->set('yubikeySecretKey', null);
		else
			$this->set('yubikeySecretKey', $yubikeySecretKey['Setting']['value']);
	
		if ($this->request->is('post') || $this->request->is('put')) {
		    
		    $error = 0;
		    
		    if($this->data['Setting']['currentYear'] == $this->data['Setting']['lastYear']){
			    $this->Session->setFlash(__('L\'année scolaire courante ne peut pas être identique à l\'année scolaire précédente !'), 'flash_error');
			    $error = 1;
		    }
		    
		    if(!is_writable($this->data['Setting']['pathBackup'])){
			    $this->Session->setFlash(__('Le répertoire de sauvegarde n\'est pas inscriptible !'), 'flash_error');
			    $error = 1;
		    }
		    
		    if(!file_exists($this->data['Setting']['pathMysqldump'])){
			    $this->Session->setFlash(__('Le chemin de l\'exécutable mysqldump est invalide !'), 'flash_error');
			    $error = 1;
		    }
		    
		    if($error == 0){
			    $this->Setting->read(null, $currentYear['Setting']['id']);
				$this->Setting->set(array('value' => $this->data['Setting']['currentYear']));
				$this->Setting->save();
				
				$this->Setting->read(null, $lastYear['Setting']['id']);
				$this->Setting->set(array('value' => $this->data['Setting']['lastYear']));
				$this->Setting->save();
				
				$this->Setting->read(null, $saveOnExit['Setting']['id']);
				$this->Setting->set(array('value' => $this->data['Setting']['saveOnExit']));
				$this->Setting->save();

				$this->Setting->read(null, $pathMysqldump['Setting']['id']);
				$this->Setting->set(array('value' => $this->data['Setting']['pathMysqldump']));
				$this->Setting->save();
				
				$this->Setting->read(null, $pathBackup['Setting']['id']);
				$this->Setting->set(array('value' => $this->data['Setting']['pathBackup']));
				$this->Setting->save();
				
				$this->Setting->read(null, $yubikeyClientId['Setting']['id']);
				$this->Setting->set(array('value' => $this->data['Setting']['yubikeyClientId']));
				$this->Setting->save();
				
				$this->Setting->read(null, $yubikeySecretKey['Setting']['id']);
				$this->Setting->set(array('value' => $this->data['Setting']['yubikeySecretKey']));
				$this->Setting->save();		
				
				$this->Session->setFlash(__('Les paramètres ont été correctement mis à jour.'), 'flash_success');
				$this->redirect(array('action'=> 'index'));
		    }	  
	    }  	
	}
	
	public function save(){
		$saveOnExit = $this->Setting->find('first', array('conditions' => array('Setting.key' => 'saveOnExit')));
		
		if($saveOnExit){
			App::Import('Model','ConnectionManager');
			$ds = ConnectionManager::getDataSource('default');
			$dsc = $ds->config;
			
			$pathMysqldump = $this->Setting->find('first', array('conditions' => array('Setting.key' => 'pathMysqldump')));
		    $pathBackup = $this->Setting->find('first', array('conditions' => array('Setting.key' => 'pathBackup')));
			shell_exec($pathMysqldump['Setting']['value']." -u ".$dsc['login']." -p".$dsc['password']." -h ".$dsc['host']." ".$dsc['database']." | gzip -c -9 > ".$pathBackup['Setting']['value']."/ocmp_".date("d_m_Y__H_i_s").".sql.gz");
			$this->redirect(array('controller'=>'users','action'=> 'logout'));	
		}
		
	}
}
