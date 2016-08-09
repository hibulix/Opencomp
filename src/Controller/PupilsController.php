<?php
namespace app\Controller;

use /** @noinspection PhpUnusedAliasInspection */
    App\Controller\AppController;
use App\Controller\Component\EncodingComponent;
use App\Controller\Component\FileUploadComponent;
use App\Model\Table\PupilsTable;
use Cake\Core\Exception\Exception;

/**
 * Pupils Controller
 *
 * @property PupilsTable $Pupils
 * @property EncodingComponent $Encoding
 * @property FileUploadComponent $FileUpload
 */
class PupilsController extends AppController {

    public $components = array('Encoding','FileUpload');

    /**
     * add method
     *
     * @return \Cake\Network\Response|null
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
     * @param $classroom_id
     */
    public function import($classroom_id) {
        if($this->request->data && $this->Pupils->isUploadedFile($this->request->data['Pupil']['exportBe1d'])){
            $err = $this->FileUpload->checkError($this->request->data['Pupil']['exportBe1d'], 'text/csv');
            if($err){
                $this->Session->setFlash(__($err),'flash_error');
            }else{
                move_uploaded_file($this->request->data['Pupil']['exportBe1d']['tmp_name'],APP.'files/import_be1d_'.$classroom_id.'.csv');
                $this->redirect(array('controller' => 'pupils', 'action' => 'parseimport', $classroom_id));
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
        $this->set('column', $this->request->data);
    }

    public function runimport($classroom_id){
        $import = $this->parsecsv(true);
        $niveaux = $this->Pupils->ClassroomsPupils->Levels->find('all', array('recursive' => -1));
        foreach($niveaux as $niveau){
            $levels[$niveau->title] = $niveau->id;
        }
        $datas = array();
        foreach($import as $line){
            $date=date_create_from_format('d/m/Y',$line[$this->request->data['birthday']]);
            $date_format= date_format($date,"Y-m-d");
            $data = array(
                'name' => $line[$this->request->data['name']],
                'first_name' => $line[$this->request->data['first_name']],
                'sex' => substr($line[$this->request->data['sex']],0,1),
                'birthday' => $date_format,
                'classrooms' => [
                    [
                        'id' => $classroom_id,
                        '_joinData' => [
                            'level_id' => $levels[$line[$this->request->data['level']]]
                        ]
                    ]
                ]
            );

            array_push($datas,$data);
        }
        $pupils = $this->Pupils->newEntities($datas);
        //debug($pupils->errors());
        $pupils_table = $this->Pupils;

        try{
            $this->Pupils->connection()->transactional(function () use ($pupils_table, $pupils) {
                foreach ($pupils as $entity) {
                    $pupils_table->save($entity, ['atomic' => false]);
                }
            });
        }catch(Exception $e){
            debug($e->getMessage());
        }

    }
    private function parsecsv($remove_first_line = false){
        $classroom_id = $this->request->params['pass'][0];
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
            $this->Flash->set(__('Le fichier n\'a pas été correctement importé.'), ['element'=>'error']);
            $this->redirect(array('controller' => 'pupils', 'action' => 'import', $classroom_id));
        }
    }
}
