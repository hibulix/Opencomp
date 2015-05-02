<?php
namespace app\Controller;

use App\Controller\AppController;

class DashboardController extends AppController {

	public function index() {
		$this->set('title_for_layout', __('Synthèse : votre tableau de bord personnel'));
		
		$this->loadModel('Classroom');
		
		$this->loadModel('Setting');
        $currentYear = $this->Setting->find('first', array('conditions' => array('Setting.key' => 'currentYear')));
		
		$this->Classroom->contain(array('Evaluation.unrated=0', 'Evaluation.Result', 'Evaluation.Item', 'Evaluation.Pupil', 'Establishment'));
		$classrooms = $this->Classroom->find('all', array(
	        'conditions' => array(
	        	'Classroom.user_id' => $this->Auth->user('id'),
	        	'Classroom.year_id' => $currentYear['Setting']['value']
	        )
	    ));
		$this->set('classrooms', $classrooms);
	}

}
