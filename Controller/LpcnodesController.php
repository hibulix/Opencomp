<?php

App::uses('AppController', 'Controller');

/**
 * Lpcnodes Controller
 *
 * @property Lpcnode $Lpcnode
 * @property Evaluation $Evaluation
 * @property Competence $Competence
 * @property Classroom $Classroom
 * @property Pupil $Pupil
 * @property Model $LpcnodesPupil
 *
 * @property array $action
 *
 * @property JsonTreeComponent $JsonTree
 * @property LivrEvalBridgeComponent $LivrEvalBridge
 */
class LpcnodesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('JsonTree', 'LivrEvalBridge');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->set('title_for_layout', __('Livret Personnel de Compétences'));
        $this->JsonTree->passAllLpcnodesToView();
	}

	public function isAuthorized($user = null) {
        if ($user['role'] === 'admin')
            return true;

		if (in_array($this->action, array('add', 'edit', 'moveup', 'movedown', 'deleteNode'))) {
            return false;
        }elseif(in_array($this->action, array('pdfCert', 'pdf', 'pdfDetail'))){
            return in_array($this->request['pass'][0], $this->Session->read('Authorized')['classrooms']);
		}else{
			return true;
		}
	}
	
	public function deleteNode($id = null) {
	    $this->Lpcnode->id = $id;
	    if (!$this->Lpcnode->exists()) {
	       throw new NotFoundException(__('Ce noeud n\'existe pas ;)'));
	    }

		$this->Lpcnode->delete();
	    $this->redirect(array('action' => 'index'));
	}

    /**
     * add method
     *
     * @param null $id
     */
	public function add($id = null) {
		$this->set('title_for_layout', __('Ajouter un noeud au Livret Personnel de Compétences'));
		if ($this->request->is('post')) {
			$this->Lpcnode->create();
			if ($this->Lpcnode->save($this->request->data)) {
				$this->Session->setFlash(__('La nouvelle compétence a été correctement ajoutée'), 'flash_success');
				if(isset($this->request->data['Lpcnode']['parent_id']))
					$this->redirect(array('action' => 'add', $this->request->data['Lpcnode']['parent_id']));
				else
					$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.'), 'flash_error');
			}
		}
		
		//On passe le paramètre à la vue
		if(isset($id) && is_numeric($id))
			$this->set('idnode', $id);
		
		//Récupération des ids des catégories existantes	
		$competenceids = $this->Lpcnode->generateTreeList(null, null, null, "");
		$this->set('cid', $competenceids);
	}

    /**
     * edit method
     *
     * @param null $id
     */
	public function edit($id = null) {
		$this->set('title_for_layout', __('Modifier un noeud du Livret Personnel de Compétences'));

		$this->Lpcnode->id = $id;
		if (!$this->Lpcnode->exists()) {
			throw new NotFoundException(__('Ce noeud n\'existe pas ;)'));
		}

		$lpcnode = $this->Lpcnode->findById($id);
		if(!$this->request->data){
			$this->request->data = $lpcnode;
		}

		if ($this->request->is('post')) {
			if ($this->Lpcnode->save($this->request->data)) {
				$this->Session->setFlash(__('Le noeud LPC a été correctement modifé'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Des erreurs ont été détectées durant la validation du formulaire. Veuillez corriger les erreurs mentionnées.'), 'flash_error');
			}
		}

		//Récupération des ids des catégories existantes
		$competenceids = $this->Lpcnode->generateTreeList(null, null, null, "");
		$this->set('cid', $competenceids);
	}

	public function linkLivreval($classroom_id = null){

        if($this->request->is('post') && isset($this->request->data['LivrEval'])){
            $this->LivrEvalBridge->setLogin($this->request->data['LivrEval']['username']);
            $this->LivrEvalBridge->setPassword($this->request->data['LivrEval']['password']);
            $response = $this->LivrEvalBridge->sendAuthenticatedRequest('https://livreval.fr/amiens/param_ecole.php');

            if(!empty($response)){
                $dom = str_get_html($response);
                $livreval_classroom_id = $dom->find('input[id=kas]',0)->getAttribute('value');
                $livreval_eleve_id = $dom->find('input[id=eleve_id_1]',0)->getAttribute('value');

                $this->loadModel('Classroom');
                $this->Classroom->id = $classroom_id;
                $this->Classroom->saveField('livreval_id', $livreval_classroom_id);
                
                $this->set('livrEval_pupils', $this->LivrEvalBridge->getPupils($livreval_eleve_id, $livreval_classroom_id));
                $this->loadModel('Evaluation');
                $this->set('pupils', $this->Evaluation->findPupilsByLevelsInClassroom($classroom_id));
            }
        }

        if($this->request->is('post') && isset($this->request->data['LivrEvalMapping'])){
            foreach ($this->request->data['LivrEvalMapping'] as $livreval_id => $pupil_id){
                $this->loadModel('Pupil');
                $this->Pupil->id = $pupil_id;
                $this->Pupil->saveField('livreval_id', $livreval_id);
            }
            $this->redirect(['controller' => 'lpcnodes', 'action' => 'getLivrEvalValidation', $classroom_id]);
        }
    }

    public function getLivrEvalValidation($classroom_id, $palier = 1){
        $this->set('classroom_id', $classroom_id);
        $this->set('palier', $palier);

        if($this->request->is('post')){
            $this->set('done', 1);
            $this->loadModel('Classroom');
            $this->Classroom->id = $classroom_id;
            $livreval_classroom_id = $this->Classroom->field('livreval_id');

            $this->loadModel('Evaluation');
            $livrEvalPupils = $this->Evaluation->findExistingLivrEvalPupilsByClassroom($classroom_id);

            foreach ($livrEvalPupils as $livreval_pupil_id => $pupil_id){
                $validated_items_livreval = $this->LivrEvalBridge->getLPCValidatedItems($livreval_pupil_id, $livreval_classroom_id, $palier);
                if(!empty($validated_items_livreval)){
                    $this->loadModel('LpcnodesPupil');

                    $mapping = $this->Lpcnode->getLpcnodeIdsFromLivrEvalIds(array_keys($validated_items_livreval), $palier);
                    $this->LpcnodesPupil->deleteAll(['pupil_id'=>$pupil_id, 'lpcnode_id IN' => $mapping]);
                    $validated_items = array_combine($mapping, $validated_items_livreval);

                    $data = [];
                    $n = 0;
                    foreach ($validated_items as $lpcnode_id => $validation_date){
                        $data[$n]['lpcnode_id'] = $lpcnode_id;
                        $data[$n]['pupil_id'] = $pupil_id;
                        $data[$n]['validation_date'] = $validation_date;
                        $n++;
                    }

                    $this->LpcnodesPupil->saveMany($data);
                }
            }
        }
    }

    public function pdfCert($classroom_id, $level_id, $palier = 1){
        $this->layout = 'ajax';

        $this->loadModel('Classroom');
        $pupils = $this->Classroom->Evaluation->findPupilsByLevelsInClassroom($classroom_id);

        $fpdf = new FPDF();
        $fpdf->SetFont('Arial','B',14);
        $fpdf->SetTextColor('16','52','166');

        foreach ($pupils as $classroom_label => $classroom){
            if($classroom_label == $level_id){
                foreach ($classroom as $pupil_id => $pupil){
                    $this->outputCertificate($pupil_id, $pupil, $palier, $fpdf, $classroom_id);
                }
            }
        }

        $pdf = $fpdf->Output();

        $this->response->body($pdf);
        $this->response->type('pdf');

        return $this->response;
    }

    public function pdfDetail($classroom_id, $level_id, $palier = 1){
        $this->layout = 'ajax';

        $this->loadModel('Classroom');
        $pupils = $this->Classroom->Evaluation->findPupilsByLevelsInClassroom($classroom_id);

        $fpdf = new FPDF();
        $fpdf->SetFont('Arial','B',14);
        $fpdf->SetTextColor('16','52','166');

        foreach ($pupils as $classroom_label => $classroom){
            if($classroom_label == $level_id){
                foreach ($classroom as $pupil_id => $pupil){
                    $page = 1;
                    $this->outputDetail($fpdf, $pupil_id, $pupil, $classroom_label, $palier, $page);
                    $fpdf->AddPage("P", "A4");
                }
            }
        }

        $pdf = $fpdf->Output();

        $this->response->body($pdf);
        $this->response->type('pdf');

        return $this->response;
    }

    public function pdf($classroom_id, $level_id, $palier = 1){
        $this->layout = 'ajax';

        $this->loadModel('Classroom');
        $pupils = $this->Classroom->Evaluation->findPupilsByLevelsInClassroom($classroom_id);

        $fpdf = new FPDF();
        $fpdf->SetFont('Arial','B',14);
        $fpdf->SetTextColor('16','52','166');

        foreach ($pupils as $classroom_label => $classroom){
            if($classroom_label == $level_id){
                foreach ($classroom as $pupil_id => $pupil){

                    $page = 1;
                    $this->outputCertificate($pupil_id, $pupil, $palier, $fpdf, $classroom_id);
                    $this->outputDetail($fpdf, $pupil_id, $pupil, $classroom_label, $palier, $page);
                }
            }
        }

        $pdf = $fpdf->Output();

        $this->response->body($pdf);
        $this->response->type('pdf');

        return $this->response;
    }

    private function outputCertificate($pupil_id, $pupil_name, $palier, FPDF $fpdf, $classroom_id){
        $validated_competences = $this->Lpcnode->getPDFLPCforPupilId($pupil_id,$palier,[2]);

        $this->loadModel('Pupil');
        $pup = $this->Pupil->find('first', array('conditions' => array('Pupil.id' => $pupil_id), 'recursive' => -1));
        $date = strtotime($pup['Pupil']['birthday']);

        $this->loadModel('Classroom');
        $classroom = $this->Classroom->find('first',array(
            'conditions' => array(
                'Classroom.id' => $classroom_id
            ),
            'contain' => array(
                'User', 'Establishment'
            )
        ));

        $fpdf->AddPage("P", "A4");
        $fpdf->SetFont('Arial','B',14);
        $fpdf->Image(WWW_ROOT.'img/lpc'.$palier.'-p1.jpg', 0, 0, $fpdf->GetPageWidth(), $fpdf->GetPageHeight());

        if($palier == 1){
            //Ecole
            $fpdf->Text(80,75, utf8_decode($classroom['Establishment']['name']));
            //Nom
            $fpdf->Text(70,113, utf8_decode($pupil_name));
            //Date naiss
            $fpdf->Text(80,118, utf8_decode(date('d/m/Y', $date)));
            $fpdf->Text(112,211, utf8_decode($classroom['User']['first_name']." ".$classroom['User']['name']));

            foreach ($validated_competences as $competence){
                $date = strtotime($competence['LpcnodesPupil']['validation_date']);
                $percent = $this->Lpcnode->getCompetenceValidationPercentage($competence['Lpcnode']['id'], $pupil_id);
                $fpdf->SetFont('Arial','B',10);
                switch ($competence['Lpcnode']['id']){
                    case '5':
                        $fpdf->Text(155,144, date('d/m/Y',$date));
                        $fpdf->Text(155,148, $percent.'% des items');
                        break;
                    case '36':
                        $fpdf->Text(155,155, date('d/m/Y',$date));
                        $fpdf->Text(155,159, $percent.'% des items');
                        break;
                    case '60':
                        $fpdf->Text(155,165, date('d/m/Y',$date));
                        $fpdf->Text(155,169, $percent.'% des items');
                        break;
                }
            }

        }elseif($palier == 2){
            //Ecole
            $fpdf->Text(80,71, utf8_decode($classroom['Establishment']['name']));
            //Nom
            $fpdf->Text(70,102, utf8_decode($pupil_name));
            //Date naiss
            $fpdf->Text(80,107, utf8_decode(date('d/m/Y', $date)));
            $fpdf->Text(112,254, utf8_decode($classroom['User']['first_name']." ".$classroom['User']['name']));

            foreach ($validated_competences as $competence){
                $date = strtotime($competence['LpcnodesPupil']['validation_date']);
                $percent = $this->Lpcnode->getCompetenceValidationPercentage($competence['Lpcnode']['id'], $pupil_id);
                $fpdf->SetFont('Arial','B',10);
                switch ($competence['Lpcnode']['id']){
                    case '68':
                        $fpdf->Text(155,127, date('d/m/Y',$date));
                        $fpdf->Text(155,131, $percent.'% des items');
                        break;
                    case '69':
                        $fpdf->Text(155,136, date('d/m/Y',$date));
                        $fpdf->Text(155,140, $percent.'% des items');
                        break;
                    case '70':
                        $fpdf->Text(155,146, date('d/m/Y',$date));
                        $fpdf->Text(155,150, $percent.'% des items');
                        $fpdf->Text(155,156, date('d/m/Y',$date));
                        $fpdf->Text(155,160, $percent.'% des items');
                        break;
                    case '71':
                        $fpdf->Text(155,166, date('d/m/Y',$date));
                        $fpdf->Text(155,170, $percent.'% des items');
                        break;
                    case '72':
                        $fpdf->Text(155,176, date('d/m/Y',$date));
                        $fpdf->Text(155,180, $percent.'% des items');
                        break;
                    case '73':
                        $fpdf->Text(155,186, date('d/m/Y',$date));
                        $fpdf->Text(155,190, $percent.'% des items');
                        break;
                    case '74':
                        $fpdf->Text(155,195, date('d/m/Y',$date));
                        $fpdf->Text(155,199, $percent.'% des items');
                        break;
                }
            }
        }
    }

    private function outputDetail(FPDF $fpdf, $pupil_id, $pupil, $classroom_label, $palier, $page){
        $lpc = $this->Lpcnode->getPDFLPCforPupilId($pupil_id,$palier,[2,3,4]);

        foreach ($lpc as $line){

            while($line['Lpcnode']['page'] != $page){
                $page++;
                $fpdf->AddPage("P", "A4");
                $fpdf->Image(WWW_ROOT.'img/lpc'.$palier.'-p'.$page.'.jpg', 0, 0, $fpdf->GetPageWidth(), $fpdf->GetPageHeight());
                $fpdf->SetFont('Arial','I',10);
                $fpdf->Text(85,283, utf8_decode($pupil)." - ". $classroom_label);
                $fpdf->SetFont('Arial','B',14);
            }

            $date = strtotime($line['LpcnodesPupil']['validation_date']);
            $fpdf->Text($line['Lpcnode']['X'],$line['Lpcnode']['Y'], date('d/m/Y',$date));
        }
    }
}


