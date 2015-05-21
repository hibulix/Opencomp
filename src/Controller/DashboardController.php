<?php
namespace app\Controller;

use Cake\ORM\TableRegistry;

class DashboardController extends AppController {

	public function index() {
		$this->set('title_for_layout', __('Synthèse : votre tableau de bord personnel'));

		$classroomsTable = TableRegistry::get('Classrooms');

		$settingsTable = TableRegistry::get('Settings');
        $currentYear = $settingsTable->find('all', array('conditions' => array('Settings.key' => 'currentYear')))->first();

		$classrooms = $classroomsTable->find('all', [
			'contain' => [
				'Evaluations' => function ($q) {
			        return $q->where(['Evaluations.unrated' => 0]);
			    },
				'Evaluations.Results',
				'Evaluations.Items',
				'Evaluations.Pupils',
				'Establishments'
			],
			'conditions' => [
	        	'Classrooms.user_id' => $this->Auth->user('id'),
	        	'Classrooms.year_id' => $currentYear->value
	        ]
		]);
		$this->set('classrooms', $classrooms);
	}

}
