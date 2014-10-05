<?php
App::uses('AppController', 'Controller');
/**
 * Pupils Controller
 *
 * @property Pupil $Pupil
 */
class PupilsController extends AppController {

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Pupil->create();
			if ($this->Pupil->save($this->request->data)) {
				$this->Session->setFlash(__('The pupil has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The pupil could not be saved. Please, try again.'));
			}
		}
		$tutors = $this->Pupil->Tutor->find('list');
		$levels = $this->Pupil->ClassroomsPupil->Level->find('list');
		$classrooms = $this->Pupil->ClassroomsPupil->Classroom->find('list');
		$this->set(compact('tutors', 'levels', 'classrooms'));
	}

    /**
     * import method
     *
     * @return void
     */
    public function import() {
        //On vérifie qu'un paramètre nommé classroom_id a été fourni et qu'il existe.
        $classroom_id = $this->CheckParams->checkForNamedParam('Classroom','classroom_id', $this->request->params['named']['classroom_id']);

        if(isset($this->request->params['named']['step']) && $this->request->params['named']['step'] == 'muf') {
            if(!empty($_FILES)){
                if ($_FILES['files']['error'][0] != 0) {
                    switch ($_FILES['files']['error'][0]){
                        case 1: // UPLOAD_ERR_INI_SIZE
                            $this->Session->setFlash(__('Le fichier envoyé est trop volumineux.'), 'flash_error');
                            $this->redirect(array('controller' => 'pupils', 'action' => 'import', 'classroom_id' => $classroom_id));
                            break;
                        case 2: // UPLOAD_ERR_FORM_SIZE
                            $this->Session->setFlash(__('Le fichier envoyé est trop volumineux.'), 'flash_error');
                            $this->redirect(array('controller' => 'pupils', 'action' => 'import', 'classroom_id' => $classroom_id));
                            break;
                        case 3: // UPLOAD_ERR_PARTIAL
                            $this->Session->setFlash(__('L\'envoi du fichier a été interrompu pendant le transfert !'), 'flash_error');
                            $this->redirect(array('controller' => 'pupils', 'action' => 'import', 'classroom_id' => $classroom_id));
                            break;
                        case 4: // UPLOAD_ERR_NO_FILE
                            $this->Session->setFlash(__('Le fichier envoyé a une taille nulle !'), 'flash_error');
                            $this->redirect(array('controller' => 'pupils', 'action' => 'import', 'classroom_id' => $classroom_id));
                            break;
                        default:
                            break;
                    }
                }else {
                    if($_FILES['files']['type'][0] != 'text/csv'){
                        $this->Session->setFlash(__('Le fichier envoyé n\'est pas un fichier .csv !'), 'flash_error');
                        $this->redirect(array('controller' => 'pupils', 'action' => 'import', 'classroom_id' => $classroom_id));
                    }else{
                        move_uploaded_file($_FILES['files']['tmp_name'][0],WWW_ROOT.'files/import_be1d_'.$classroom_id.'.csv');
                        $this->redirect(array('controller' => 'pupils', 'action' => 'parseimport', 'classroom_id' => $classroom_id));
                    }
                }
            }
        }
    }

    public function parseimport(){
        //On vérifie qu'un paramètre nommé classroom_id a été fourni et qu'il existe.
        $classroom_id = $this->CheckParams->checkForNamedParam('Classroom','classroom_id', $this->request->params['named']['classroom_id']);

        if(file_exists('files/import_be1d_'.$classroom_id.'.csv')){
            if (($handle = fopen('files/import_be1d_'.$classroom_id.'.csv', 'r')) !== FALSE) {
                $i = 0;

                $niveaux = $this->Pupil->ClassroomsPupil->Level->find('all', array('recursive' => -1));
                foreach($niveaux as $niveau){
                    $levels[$niveau['Level']['title']] = $niveau['Level']['id'];
                }

                while (($data = fgetcsv($handle, 1000, ";", '"')) !== FALSE) {
                    if($i != 0){
                        $import[$i]['nom'] = mb_convert_encoding($data[0],'UTF-8','ISO-8859-1');
                        $import[$i]['prenom'] = mb_convert_encoding($data[1],'UTF-8','ISO-8859-1');
                        $import[$i]['datenaiss'] = $data[9];
                        $import[$i]['datebase'] = substr($data[9],6,4).'-'.substr($data[9],3,2).'-'.substr($data[9],0,2);
                        $import[$i]['niveau'] = $data[2];
                        $import[$i]['niveaubase'] = $levels[$data[2]];
                        $import[$i]['sexe'] = substr($data[13],0,1);
                    }
                    $i++;
                }
                fclose($handle);
                $this->set('preview',$import);
            }

        }else{
            $this->Session->setFlash(__('Le fichier n\'a pas été correctement importé.'), 'flash_error');
            $this->redirect(array('controller' => 'pupils', 'action' => 'import', 'classroom_id' => $classroom_id));
        }

        if(isset($this->request->params['named']['step']) && $this->request->params['named']['step'] == 'go') {
            $this->_runImport($import);
        }
    }
    
    private function _runImport($import){
        //On vérifie qu'un paramètre nommé classroom_id a été fourni et qu'il existe.
        $classroom_id = $this->CheckParams->checkForNamedParam('Classroom','classroom_id', $this->request->params['named']['classroom_id']);
        
        $datas = array();
        foreach($import as $pupil){
            $data = array(
                'Pupil' => array('name' => $pupil['nom'],'first_name' => $pupil['prenom'],'sex' => $pupil['sexe'],'birthday' => $pupil['datebase']),
                'ClassroomsPupil' => array(array('classroom_id' => $classroom_id, 'level_id' => $pupil['niveaubase']))
            );
            array_push($datas,$data);
        }
        if($this->Pupil->saveMany($datas, array('deep' => true, 'atomic' => true))){
            $this->Session->setFlash(__('Les élèves ont été correctement importés.'), 'flash_success');
            $this->redirect(array('controller' => 'classrooms', 'action' => 'view', $classroom_id));
            unlink('/files/import_be1d_'.$classroom_id.'.csv');
        }else{
            unlink('/files/import_be1d_'.$classroom_id.'.csv');
            $this->Session->setFlash(__('Une erreur est survenue lors de l\'import'), 'flash_error');
            $this->redirect(array('controller' => 'pupils', 'action' => 'parseimport', 'classroom_id' => $classroom_id));
        }
    }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Pupil->id = $id;
		if (!$this->Pupil->exists()) {
			throw new NotFoundException(__('L\'élève spécifié n\'existe pas !'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->Pupil->ClassroomsPupil->set(array(
			    'classroom_id' => 10,
			    'pupil_id' => 13,
			    'level_id' => 7
			));
			if ($this->Pupil->saveAll($this->request->data)) {
				$this->Session->setFlash(__('The pupil has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The pupil could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Pupil->read(null, $id);
		}
		$tutors = $this->Pupil->Tutor->find('list');
		$levels = $this->Pupil->ClassroomsPupil->Level->find('list');
		$classrooms = $this->Pupil->ClassroomsPupil->Classroom->find('list');
		$this->set(compact('tutors', 'levels', 'classrooms'));
	}

/**
 * delete method
 *
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Pupil->id = $id;
		if (!$this->Pupil->exists()) {
			throw new NotFoundException(__('Invalid pupil'));
		}
		if ($this->Pupil->delete()) {
			$this->Session->setFlash(__('Pupil deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Pupil was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
