<?php

namespace app\Controller;

use App\Controller\AppController;
use App\Model\Table\ClassroomsPupilsTable;
use Cake\I18n\Time;
use Fusonic\SpreadsheetExport\ColumnTypes\TextColumn;
use Fusonic\SpreadsheetExport\Spreadsheet;
use Fusonic\SpreadsheetExport\Writers\OdsWriter;

/**
 * ClassroomsPupils Controller
 *
 * @property ClassroomsPupilsTable $ClassroomsPupils
 */
class ClassroomsPupilsController extends AppController
{


    /**
     * @param null $id classroom_pupil id
     * @return void
     */
    public function edit($id = null)
    {
        $classroomPupil = $this->ClassroomsPupils->find()
            ->contain(['Classrooms', 'Pupils'])
            ->where(['Pupils.id' => $id])->firstOrFail();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $classroomPupil = $this->ClassroomsPupils->patchEntity($classroomPupil, $this->request->data);

            if ($this->ClassroomsPupils->save($classroomPupil)) {
                $this->Flash->success('L\' élève a été correctement modifié.');
                $this->redirect(['controller' => 'Classrooms', 'action' => 'view', $classroomPupil->classroom->id]);
            } else {
                $this->Flash->error('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.');
            }
        }

        $levels = $this->ClassroomsPupils->Levels->find('list');
        $this->set(compact('classroomPupil', 'levels'));
    }

    /**
     * import method
     *
     * @return \Cake\Network\Response
     */
    public function opendocumentExport()
    {
        $classroom = $this->ClassroomsPupils->Classrooms->get($this->request->query['classroom_id']);

        //Récupération des élève de la classe courante
        $pupils = $this->ClassroomsPupils->find('all', [
            'conditions' => ['classroom_id' => $classroom->id],
            'contain' => ['Pupils', 'Levels']
        ]);
        $export = new Spreadsheet();

        //En-têtes de colonnes
        $export->AddColumn(new TextColumn("Code"));
        $export->AddColumn(new TextColumn("Nom"));
        $export->AddColumn(new TextColumn("Prénom"));
        $export->AddColumn(new TextColumn("naiss"));

        //Ajout des élèves au fichier Excel
        foreach ($pupils as $pupil) {
            $export->AddRow([
                '*00' . $pupil->pupil->id . '*',
                $pupil->pupil->name,
                $pupil->pupil->first_name,
                Time::parse($pupil->pupil->birthday)->i18nFormat('dd/MM/YYYY')
            ]);
        }

        //Ajout de l'enseignant
        $export->AddRow([
            '',
            $this->request->session()->read('Auth.User.name'),
            $this->request->session()->read('Auth.User.first_name'),
            ''
        ]);

        //Envoi du fichier Excel à l'utilisateur
        $writer = new OdsWriter();
        $writer->includeColumnHeaders = true;
        $savePath = APP . "/files/pupils_ods/pupils_" . $this->request->query['classroom_id'] . ".ods";
        $export->Save($writer, $savePath);

        $this->response->file(
            $savePath,
            ['download' => true]
        );

        return $this->response;
    }

    /**
     * @param null $id classroom_pupil id
     * @return \Cake\Network\Response|null
     */
    public function unlink($id = null)
    {
        $classroomPupil = $this->ClassroomsPupils->get($id);
        $classroomId = $classroomPupil->classroom->id;
        $this->request->allowMethod(['post', 'delete']);
        if ($this->ClassroomsPupils->delete($classroomPupil)) {
            $this->Flash->success('L\'élève a été correctement dissocié de cette classe.');
        } else {
            $this->Flash->error('L\'élève n\'a pas pu être dissocié de cette classe.');
        }


        return $this->redirect(['controller' => 'Classrooms', 'action' => 'view', $classroomId]);
    }
}
