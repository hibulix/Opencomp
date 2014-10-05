<?php
App::uses('AppController', 'Controller');
/**
 * ClassroomsPupils Controller
 *
 * @property ClassroomsPupil $ClassroomsPupil
 */
class ClassroomsPupilsController extends AppController {
	

	public function add(){
		
		//On vérifie qu'un paramètre nommé classroom_id a été fourni et qu'il existe.
		if(isset($this->request->params['named']['classroom_id'])) {
       		$classroom_id = intval($this->request->params['named']['classroom_id']);
       		$this->set('classroom_id', $classroom_id);
       		$this->ClassroomsPupil->Classroom->id = $classroom_id;
			if (!$this->ClassroomsPupil->Classroom->exists()) {
				throw new NotFoundException(__('The classroom_id provided does not exist !'));
			}
		} else {
			throw new NotFoundException(__('You must provide a classroom_id in order to edit this pupil !'));
		}
		
		$existing_pupils = $this->ClassroomsPupil->Pupil->find('list');
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->redirect(array('action' => 'edit', 'classroom_id' => $classroom_id, $this->data['Pupil']['id']));
		}else{
			if(empty($existing_pupils)){
				$this->redirect(array('action' => 'addnew', 'classroom_id' => $classroom_id));
			}else{
				$this->set('existing_pupils', $existing_pupils);
			}
		}
	}

	/**
     * import method
     *
     * @return void
     */
    public function excelExport() {
        //On vérifie qu'un paramètre nommé classroom_id a été fourni et qu'il existe.
        $classroom_id = $this->CheckParams->checkForNamedParam('Classroom','classroom_id', $this->request->params['named']['classroom_id']);
        
        //Récupération des élève de la classe courante
        $pupils = $this->ClassroomsPupil->find('all', array(
        	'conditions' => array('classroom_id' => $classroom_id),
        	'contain' => array('Pupil.id', 'Pupil.first_name', 'Pupil.name', 'Pupil.birthday', 'Level.title')
        ));        

        $objPHPExcel = new PHPExcel();

        //Propriétés du fichier et en-tête
        $objPHPExcel->getProperties()->setCreator("Opencomp system");
        $objPHPExcel->getProperties()->setLastModifiedBy("Opencomp system");
        $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Code');
    	$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Nom');
    	$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Prénom');
    	$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'naiss');

    	$num;

    	//Ajout des élèves au fichier Excel
        foreach ($pupils as $line => $pupil) {
        	$num = $line+2;
        	$objPHPExcel->setActiveSheetIndex(0);
        	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$num, '*00'.$pupil['Pupil']['id'].'*');
        	$objPHPExcel->getActiveSheet()->SetCellValue('B'.$num, $pupil['Pupil']['name']);
        	$objPHPExcel->getActiveSheet()->SetCellValue('C'.$num, $pupil['Pupil']['first_name']);
        	$objPHPExcel->getActiveSheet()->SetCellValue('D'.$num, date_format(new DateTime($pupil['Pupil']['birthday']),'d/m/Y'));
        }

        $num++;

		//Ajout de l'enseignant
        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$num, '');
    	$objPHPExcel->getActiveSheet()->SetCellValue('B'.$num, AuthComponent::user('name'));
    	$objPHPExcel->getActiveSheet()->SetCellValue('C'.$num, AuthComponent::user('first_name'));
    	$objPHPExcel->getActiveSheet()->SetCellValue('D'.$num, '');

    	//Envoi du fichier Excel à l'utilisateur
        $objPHPExcel->getActiveSheet()->setTitle('Pupils');
        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename="export-pupils.xls"');
		$objWriter->save('php://output');
    }
	
	public function addnew(){
		
		//On vérifie qu'un paramètre nommé classroom_id a été fourni et qu'il existe.
		if(isset($this->request->params['named']['classroom_id'])) {
       		$classroom_id = intval($this->request->params['named']['classroom_id']);
       		$this->set('classroom_id', $classroom_id);
       		$this->ClassroomsPupil->Classroom->id = $classroom_id;
			if (!$this->ClassroomsPupil->Classroom->exists()) {
				throw new NotFoundException(__('The classroom_id provided does not exist !'));
			}
		} else {
			throw new NotFoundException(__('You must provide a classroom_id in order to edit this pupil !'));
		}
       
		
		if ($this->request->is('post') || $this->request->is('put')) {
						
			if ($this->ClassroomsPupil->saveAll($this->request->data)) {
				$this->Session->setFlash(__('Le nouvel élève a correctement été ajouté.'), 'flash_success');
				$this->redirect(array('controller' => 'classrooms', 'action' => 'view', $classroom_id));
			} else {
				$this->Session->setFlash(__('The pupil could not be saved. Please, try again.'));
			}
		}
		$levels = $this->ClassroomsPupil->Level->find('list');

		$this->set(compact('levels'));
		
	}
	
	public function import(){
		
		//On vérifie qu'un paramètre nommé classroom_id a été fourni et qu'il existe.
		if(isset($this->request->params['named']['classroom_id'])) {
       		$classroom_id = intval($this->request->params['named']['classroom_id']);
       		$this->set('classroom_id', $classroom_id);
       		$this->ClassroomsPupil->Classroom->id = $classroom_id;
			if (!$this->ClassroomsPupil->Classroom->exists()) {
				throw new NotFoundException(__('The classroom_id provided does not exist !'));
			}
		} else {
			throw new NotFoundException(__('You must provide a classroom_id in order to import pupils in this class !'));
		}
	}
	
	public function edit($id = null) {
	
		//On commence par vérifier que l'id de l'élève passé existe.
       	$this->ClassroomsPupil->Pupil->id = $id;
		if (!$this->ClassroomsPupil->Pupil->exists()) {
			throw new NotFoundException(__('You must provide an existing pupil_id to perform this editing action !'));
		}
       
		//On vérifie qu'un paramètre nommé classroom_id a été fourni et qu'il existe.
		if(isset($this->request->params['named']['classroom_id'])) {
       		$classroom_id = intval($this->request->params['named']['classroom_id']);
       		$this->set('classroom_id', $classroom_id);
       		$this->ClassroomsPupil->Classroom->id = $classroom_id;
			if (!$this->ClassroomsPupil->Classroom->exists()) {
				throw new NotFoundException(__('The classroom_id provided does not exist !'));
			}
		} else {
			throw new NotFoundException(__('You must provide a classroom_id in order to edit this pupil !'));
		}
		
		if ($this->request->is('post') || $this->request->is('put')) {
			
			//Si aucune erreur n'a été rencontré durant la validation du formulaire, on commence 
			//par supprimer toutes le niveau précédemment associé à l'élève pour cette classe.
			if(empty($this->validationErrors)){
				$to_delete = $this->ClassroomsPupil->find('list', array(
			        'fields' => array('ClassroomsPupil.id'),
			        'conditions' => array(
			        	'ClassroomsPupil.classroom_id =' => $classroom_id,
			        	'ClassroomsPupil.pupil_id =' => $id),
			        'recursive' => 0
			    ));
			    
			    foreach($to_delete as $association)
			    	$this->ClassroomsPupil->delete($association);
			}
			
			if ($this->ClassroomsPupil->saveAll($this->request->data)) {
				$this->Session->setFlash(__('The pupil has been saved'));
				$this->redirect(array('controller' => 'classrooms', 'action' => 'view', $classroom_id));
			} else {
				$this->Session->setFlash(__('The pupil could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->ClassroomsPupil->Pupil->read(null, $id);
		}
		$levels = $this->ClassroomsPupil->Level->find('list');
		$classrooms = $this->ClassroomsPupil->Classroom->find('list');
		$this->set(compact('tutors', 'levels', 'classrooms'));
    }
    
    public function unlink($id = null){
    
	    //On vérifie qu'un paramètre nommé classroom_id a été fourni et qu'il existe.
		if(isset($this->request->params['named']['classroom_id'])) {
       		$classroom_id = intval($this->request->params['named']['classroom_id']);
       		$this->set('classroom_id', $classroom_id);
       		$this->ClassroomsPupil->Classroom->id = $classroom_id;
			if (!$this->ClassroomsPupil->Classroom->exists()) {
				throw new NotFoundException(__('The classroom_id provided does not exist !'));
			}else{
				$to_delete = $this->ClassroomsPupil->find('list', array(
			        'fields' => array('ClassroomsPupil.id'),
			        'conditions' => array(
			        	'ClassroomsPupil.classroom_id =' => $classroom_id,
			        	'ClassroomsPupil.pupil_id =' => $id),
			        'recursive' => 0
			    ));
			    
			    foreach($to_delete as $association)
			    	$this->ClassroomsPupil->delete($association);
			    	
			    $this->Session->setFlash(__('L\'élève a correctement été supprimé de cette classe.'),'flash_success');			    	
			    $this->redirect(array('controller' => 'classrooms', 'action' => 'view', $classroom_id));
			}
		} else {
			throw new NotFoundException(__('You must provide a classroom_id in order to unlink this pupil from a classroom !'));
		}
		
    }
}
