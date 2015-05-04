<?php
namespace app\Controller;

use App\Controller\AppController;
use App\Model\Entity\Pupil;

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
        $classroom = $this->Pupils->Classrooms->get($this->request->query['classroom_id']);

        $pupil = $this->Pupils->newEntity();
        if ($this->request->is('post')) {
            $pupil = $this->Pupils->newEntity($this->request->data);
            $class = $this->Pupils->ClassroomsPupils->newEntity();
            $class->classroom_id = $classroom->id;
            $class->level_id = $this->request->data['level_id'];
            $pupil->classrooms_pupils = [$class];
            if ($this->Pupils->save($pupil)) {
                $this->Flash->success('Le nouvel élève a été correctement ajouté.');
                return $this->redirect(['controller' => 'Classrooms', 'action' => 'view', $classroom->id]);
            } else {
                $this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
            }
        }

		$tutors = $this->Pupils->Tutors->find('list');
		$levels = $this->Pupils->ClassroomsPupils->Levels->find('list');
		$classrooms = $this->Pupils->ClassroomsPupils->Classrooms->find('list');
		$this->set(compact('pupil', 'classroom', 'tutors', 'levels', 'classrooms'));
	}

    /**
     * import method
     *
     * @return void
     */
    public function import() {
        $this->set('title_for_layout', 'Importer un export BE1D');

        $classroom = $this->Pupils->Classrooms->get($this->request->query['classroom_id']);

        if($this->request->data && $this->isUploadedFile($this->request->data['exportBe1d'])){
            $err = $this->FileUpload->checkError($this->request->data['exportBe1d'], 'text/csv');

            if($err){
                $this->Flash->error('er');
            }else{
                move_uploaded_file($this->request->data['exportBe1d']['tmp_name'],APP.'files/import_be1d_'.$classroom->id.'.csv');
                $this->redirect(array('controller' => 'pupils', 'action' => 'parseimport', 'classroom_id' => $classroom->id));
            }
        }

        $this->set(compact('classroom'));
    }

    private function isUploadedFile($params) {
        if ((isset($params['error']) && $params['error'] == 0) ||
            (!empty( $params['tmp_name']) && $params['tmp_name'] != 'none')
        ) {
            return is_uploaded_file($params['tmp_name']);
        }
        return false;
    }

    public function parseimport(){
        $this->set('title_for_layout', 'Aperçu avant import BE1D');

        $classroom = $this->Pupils->Classrooms->get($this->request->query['classroom_id']);
        $this->set(compact('classroom'));

        if(file_exists(APP.'files/import_be1d_'.$classroom->id.'.csv')){
            $csv_file = file(APP.'files/import_be1d_'.$classroom->id.'.csv');
            array_shift($csv_file);
            foreach($csv_file as $line)
                $csv_array[] = str_getcsv($line,';','"');

            $this->set('preview', $this->Encoding->convertArrayToUtf8($csv_array));
        }else{
            $this->Flash->error('Le fichier n\'a pas été correctement importé.');
            $this->redirect(array('controller' => 'pupils', 'action' => 'import', 'classroom_id' => $classroom->id));
        }

        if(isset($this->request->query['step']) && $this->request->query['step'] == 'go')
            $this->runImport($this->Encoding->convertArrayToUtf8($csv_array));
    }
    
    private function runImport($import){
        $classroom_id = $this->request->query['classroom_id'];

        $niveaux = $this->Pupils->ClassroomsPupils->Levels->find('all');
        foreach($niveaux as $niveau){
            $levels[$niveau->title] = $niveau->id;
        }

        foreach($import as $line){
            $pupil = $this->Pupils->newEntity();
            $pupil->name = $line[0];
            $pupil->first_name = $line[1];
            $pupil->sex = $line[13];
            $pupil->birthday = substr($line[9],6,4).'-'.substr($line[9],3,2).'-'.substr($line[9],0,2);

            $class = $this->Pupils->ClassroomsPupils->newEntity();
            $class->classroom_id = $classroom_id;
            $class->level_id = $levels[$line[2]];

            $pupil->classrooms_pupils = [$class];

            $pupilEntities[] = $pupil;
        }

        $pupilsTable = $this->Pupils;
        $pupilsOk = $pupilsTable->connection()->transactional(function () use ($pupilsTable, $pupilEntities) {
            foreach ($pupilEntities as $entity) {
                $pupilsTable->save($entity, ['atomic' => false]);
            }
        });

        if($pupilsOk !== false){
            $this->Flash->success('Les élèves ont été correctement importés.');
            unlink(APP.'files/import_be1d_'.$classroom_id.'.csv');
            $this->redirect(array('controller' => 'classrooms', 'action' => 'view', $classroom_id));
        }else{
            unlink(APP.'files/import_be1d_'.$classroom_id.'.csv');
            $this->Flash->error('Une erreur est survenue lors de l\'import');
            $this->redirect(array('controller' => 'pupils', 'action' => 'parseimport', 'classroom_id' => $classroom_id));
        }
    }
}
