<?php
namespace app\Controller;

use App\Controller\AppController;
use Cake\Core\Exception\Exception;

/**
 * Pupils Controller
 *
 * @property \App\Model\Table\PupilsTable $Pupils
 * @property \App\Controller\Component\EncodingComponent $Encoding
 * @property \App\Controller\Component\FileUploadComponent $FileUpload
 */
class PupilsController extends AppController
{
    public $components = ['Encoding', 'FileUpload'];

    /**
     * add method
     *
     * @return \Cake\Network\Response|null
     */
    public function add()
    {
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
     * @param int $classroomId classroom identifier
     * @return void
     */
    public function import($classroomId)
    {
        if ($this->request->data && $this->Pupils->isUploadedFile($this->request->data['Pupil']['exportBe1d'])) {
            $err = $this->FileUpload->checkError($this->request->data['Pupil']['exportBe1d'], 'text/csv');
            if ($err) {
                $this->Session->setFlash(__($err), 'flash_error');
            } else {
                move_uploaded_file($this->request->data['Pupil']['exportBe1d']['tmp_name'], APP . 'files/import_be1d_' . $classroomId . '.csv');
                $this->redirect(['controller' => 'pupils', 'action' => 'parseimport', $classroomId]);
            }
        }
    }

    /**
     * @return void
     */
    public function parseimport()
    {
        $import = $this->parsecsv();
        $this->set('preview', $import);
    }

    /**
     * @return void
     */
    public function previewimport()
    {
        $import = $this->parsecsv(true);
        $this->set('preview', $import);
        $this->set('column', $this->request->data);
    }

    /**
     * @param int $classroomId Classroom identifier where pupils from file are added
     * @return void
     */
    public function runimport($classroomId)
    {
        $import = $this->parsecsv(true);
        $niveaux = $this->Pupils->ClassroomsPupils->Levels->find('all', ['recursive' => -1]);
        foreach ($niveaux as $niveau) {
            $levels[$niveau->title] = $niveau->id;
        }
        $datas = [];
        foreach ($import as $line) {
            $date = date_create_from_format('d/m/Y', $line[$this->request->data['birthday']]);
            $dateFormat = date_format($date, "Y-m-d");
            $data = [
                'name' => $line[$this->request->data['name']],
                'first_name' => $line[$this->request->data['first_name']],
                'sex' => substr($line[$this->request->data['sex']], 0, 1),
                'birthday' => $dateFormat,
                'classrooms' => [
                    [
                        'id' => $classroomId,
                        '_joinData' => [
                            'level_id' => $levels[$line[$this->request->data['level']]]
                        ]
                    ]
                ]
            ];

            array_push($datas, $data);
        }
        $pupils = $this->Pupils->newEntities($datas);
        $pupilsTable = $this->Pupils;

        try {
            $this->Pupils->connection()->transactional(function () use ($pupilsTable, $pupils) {
                foreach ($pupils as $entity) {
                    $pupilsTable->save($entity, ['atomic' => false]);
                }
            });
        } catch (Exception $e) {
            debug($e->getMessage());
        }
    }

    /**
     * @param bool $removeFirstLine Should we remove header line
     * @return mixed
     */
    public function parsecsv($removeFirstLine = false)
    {
        $classroomId = $this->request->params['pass'][0];
        if (file_exists(APP . 'files/import_be1d_' . $classroomId . '.csv')) {
            $csvFile = file(APP . 'files/import_be1d_' . $classroomId . '.csv');
            if ($removeFirstLine) {
                array_shift($csvFile);
            }
            foreach ($csvFile as $line) {
                $csvArray[] = str_getcsv($line, ';', '"');
            }
            $this->set('classroom_id', $classroomId);


            return $this->Encoding->convertArrayToUtf8($csvArray);
        } else {
            $this->Flash->set(__('Le fichier n\'a pas été correctement importé.'), ['element' => 'error']);
            $this->redirect(['controller' => 'pupils', 'action' => 'import', $classroomId]);
        }
    }
}
