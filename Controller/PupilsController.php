<?php
App::uses('AppController', 'Controller');
App::uses('ReportFormaterHelper', 'View/Helper');
/**
 * Pupils Controller
 *
 * @property Pupil $Pupil
 */
class PupilsController extends AppController {

    public $components = array('Encoding','FileUpload');

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
        $classroom_id = $this->CheckParams->checkForNamedParam('Classroom','classroom_id', $this->request->params['named']['classroom_id']);

        if($this->request->data && $this->Pupil->isUploadedFile($this->request->data['Pupil']['exportBe1d'])){
            $err = $this->FileUpload->checkError($this->request->data['Pupil']['exportBe1d'], 'text/csv');

            if($err){
                $this->Session->setFlash(__($err),'flash_error');
            }else{
                move_uploaded_file($this->request->data['Pupil']['exportBe1d']['tmp_name'],APP.'files/import_be1d_'.$classroom_id.'.csv');
                $this->redirect(array('controller' => 'pupils', 'action' => 'parseimport', 'classroom_id' => $classroom_id));
            }
        }
    }

    public function parseimport(){
		$import = $this->parsecsv();
		$this->set('preview',$import);
	}

	public function previewimport(){
		$import = $this->parsecsv(true);

		$this->set('preview', $import);
		$this->set('column', $this->request->data['Pupil']);
	}
    
    public function runimport(){
        $import = $this->parsecsv(true);
		$classroom_id = $this->request->params['named']['classroom_id'];
        $niveaux = $this->Pupil->ClassroomsPupil->Level->find('all', array('recursive' => -1));
        foreach($niveaux as $niveau){
            $levels[$niveau['Level']['title']] = $niveau['Level']['id'];
        }

        $datas = array();
        foreach($import as $line){
            $data = array(
                'Pupil' => array(
                    'name' => $line[$this->request->data['Pupil']['name']],
                    'first_name' => $line[$this->request->data['Pupil']['first_name']],
                    'sex' => $line[$this->request->data['Pupil']['sex']],
                    'birthday' => $line[$this->request->data['Pupil']['birthday']]
                ),
                'ClassroomsPupil' => array(
                    array(
                        'classroom_id' => $classroom_id,
                        'level_id' => $levels[$line[$this->request->data['Pupil']['level']]]
                    )
                )
            );
            array_push($datas,$data);
        }

        if($this->Pupil->saveMany($datas, array('deep' => true, 'atomic' => true))){
            $this->Session->setFlash(__('Les élèves ont été correctement importés.'), 'flash_success');
            unlink(APP.'files/import_be1d_'.$classroom_id.'.csv');
            $this->redirect(array('controller' => 'classrooms', 'action' => 'view', $classroom_id));
        }else{
            unlink(APP.'files/import_be1d_'.$classroom_id.'.csv');
            $this->Session->setFlash(__('Une erreur est survenue lors de l\'import'), 'flash_error');
            $this->redirect(array('controller' => 'pupils', 'action' => 'parseimport', 'classroom_id' => $classroom_id));
        }
    }

	private function parsecsv($remove_first_line = false){
		$classroom_id = $this->request->params['named']['classroom_id'];

		if(file_exists(APP.'files/import_be1d_'.$classroom_id.'.csv')){
			$csv_file = file(APP.'files/import_be1d_'.$classroom_id.'.csv');

			if($remove_first_line){
				array_shift($csv_file);
			}
			foreach($csv_file as $line)
				$csv_array[] = str_getcsv($line,';','"');

			$this->set('classroom_id',$classroom_id);

			return $this->Encoding->convertArrayToUtf8($csv_array);
		}else{
			$this->Session->setFlash(__('Le fichier n\'a pas été correctement importé.'), 'flash_error');
			$this->redirect(array('controller' => 'pupils', 'action' => 'import', 'classroom_id' => $classroom_id));
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
