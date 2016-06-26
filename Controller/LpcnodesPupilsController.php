<?php

App::uses('AppController', 'Controller');

/**
 * Class LpcnodesPupilsController
 *
 * @property Classroom $Classroom
 */
class LpcnodesPupilsController extends AppController{

    public function isAuthorized($user = null)
    {
        //Vérification de l'existance de la classe
        $this->loadModel('Classroom');
        $this->Classroom->id = $this->request['pass'][2];
        if (!$this->Classroom->exists()) {
            return false;
        }

        //L'administrateur a toujours le droit
        if($user['role'] === 'admin'){
            return true;
        }else{
            //L'élève n'est pas lié à la classe, accès refusé
            if($this->Classroom->pupilIsLinkedToClassroom($this->Classroom->id, $this->request['pass'][1]) == 0){
                return false;
            }

            //La classe courante est elle dans les classe pour lesquelle l'accès est autorisé à l'utilisateur ?
            return in_array($this->Classroom->id, $this->Session->read('Authorized')['classrooms']);
        }
    }

    public function validate_lpcitem($lpcnode_id, $pupil_id, $classroom_id, $palier){
        $data = array(
          'LpcnodesPupil' => array(
              'lpcnode_id' => $lpcnode_id,
              'pupil_id' => $pupil_id,
              'validation_date' => date('Y-m-d'),
              'type_val' => 'M'
          )
        );
        $this->LpcnodesPupil->save($data);

        $this->redirect(array(
            'controller' => 'classrooms',
            'action' => 'viewlpc',
            $classroom_id,
            $palier,
            $pupil_id
        ));
    }

    public function unvalidate_lpcitem($lpcnode_id, $pupil_id, $classroom_id, $palier){

        $conditions = array(
            'LpcnodesPupil.lpcnode_id' => $lpcnode_id,
            'LpcnodesPupil.pupil_id' => $pupil_id,
            'LpcnodesPupil.type_val' => 'M'
        );

        $this->LpcnodesPupil->deleteAll($conditions);

        $this->redirect(array(
            'controller' => 'classrooms',
            'action' => 'viewlpc',
            $classroom_id,
            $palier,
            $pupil_id
        ));
    }
}