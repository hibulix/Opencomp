<?php
App::uses('AppModel', 'Model');
/**
 * Evaluation Model
 *
 * @property Classroom $Classroom
 * @property User $User
 * @property Period $Period
 * @property Result $Result
 * @property Item $Item
 */
class Evaluation extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';
	public $actsAs = array('Containable');

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'title' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Vous devez saisir un titre pour la nouvelle évaluation.',
			),
		),
		'classroom_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'period_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Classroom' => array(
			'className' => 'Classroom',
			'foreignKey' => 'classroom_id',
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
		),
		'Period' => array(
			'className' => 'Period',
			'foreignKey' => 'period_id',
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Result' => array(
			'className' => 'Result',
			'foreignKey' => 'evaluation_id',
			'dependent' => false,
		),
		'EvaluationsItem' => array(
			'className' => 'EvaluationsItem',
			'foreignKey' => 'evaluation_id',
			'dependent' => false,
		),
        'EvaluationsPupil' => array(
            'className' => 'EvaluationsPupil',
            'foreignKey' => 'evaluation_id',
            'dependent' => false,
        )
	);


/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Item' => array(
			'className' => 'Item',
			'joinTable' => 'evaluations_items',
			'foreignKey' => 'evaluation_id',
			'associationForeignKey' => 'item_id',
			'unique' => 'keepExisting',
		),
		'Pupil' => array(
			'className' => 'Pupil',
			'joinTable' => 'evaluations_pupils',
			'foreignKey' => 'evaluation_id',
			'associationForeignKey' => 'pupil_id',
			'unique' => 'keepExisting',
		)
	);

	public function findItemsByPosition($evaluation_id){
		$items = $this->find('all', array(
	        'joins' => array(
			    array('table' => 'evaluations_items',
			        'alias' => 'EvaluationsItem',
			        'type' => 'LEFT',
			        'conditions' => array(
			            'Evaluation.id = EvaluationsItem.evaluation_id',
			        )
			    ),
			    array('table' => 'items',
			        'alias' => 'Item',
			        'type' => 'LEFT',
			        'conditions' => array(
			            'EvaluationsItem.item_id = Item.id',
			        )
			    )
			 ),
			 'recursive' => -1,
			 'fields' => array('EvaluationsItem.position','Item.title','Item.id', 'Evaluation.title'),
			 'conditions' => array('Evaluation.id' => $evaluation_id),
	         'order' => array('EvaluationsItem.position'),
	    ));

	    return $items;
	}

	public function findPupilsByLevelsInClassroom($id_classroom){
		$levels = $this->Pupil->ClassroomsPupil->Level->find('list', array(
			'conditions' => array(
				'ClassroomsPupil.classroom_id' => $id_classroom
			),
			'recursive' => -1,
			'fields' => 'ClassroomsPupil.level_id',
			'joins' => array(
			    array('table' => 'classrooms_pupils',
			        'alias' => 'ClassroomsPupil',
			        'type' => 'LEFT',
			        'conditions' => array(
			            'Level.id = ClassroomsPupil.level_id',
			        ),
			    )
			 )
		));

		$pupils = $this->Pupil->ClassroomsPupil->find('all', array(
			'conditions' => array(
				'ClassroomsPupil.classroom_id' => $id_classroom,
				'ClassroomsPupil.level_id' => $levels
			),
			'recursive' => -1,
			'fields' => array('Pupil.id','Pupil.first_name','Pupil.name','Level.title'),
			'joins' => array(
			    array('table' => 'pupils',
			        'alias' => 'Pupil',
			        'type' => 'LEFT',
			        'conditions' => array(
			            'Pupil.id = ClassroomsPupil.pupil_id',
			        ),
			    ),
			    array('table' => 'levels',
			        'alias' => 'Level',
			        'type' => 'LEFT',
			        'conditions' => array(
			            'Level.id = ClassroomsPupil.level_id',
			        ),
			    )
			 ),
			'order' => array(
				'Level.id', 'Pupil.name', 'Pupil.first_name'
			)
		));

		foreach($pupils as $pupil){
			$pupilsLevels[$pupil['Level']['title']][$pupil['Pupil']['id']] = $pupil['Pupil']['first_name'].' '.$pupil['Pupil']['name'];
		}

		return $pupilsLevels;
	}

	public function findExistingLivrEvalPupilsByClassroom($id_classroom){

		$pupils = $this->Pupil->ClassroomsPupil->find('all', array(
			'conditions' => array(
				'ClassroomsPupil.classroom_id' => $id_classroom,
				'Pupil.livreval_id IS NOT NULL'
			),
			'recursive' => -1,
			'fields' => array('Pupil.id','Pupil.livreval_id'),
			'joins' => array(
				array('table' => 'pupils',
					'alias' => 'Pupil',
					'type' => 'LEFT',
					'conditions' => array(
						'Pupil.id = ClassroomsPupil.pupil_id',
					),
				)
			),
			'order' => array(
				'Pupil.name', 'Pupil.first_name'
			)
		));

		$listPupils = [];
		foreach($pupils as $pupil){
			$listPupils[$pupil['Pupil']['livreval_id']] = $pupil['Pupil']['id'];
		}

		return $listPupils;
	}

	public function findPupilsByLevelsInEvaluation($id_evaluation){
		$evaluation = $this->find('first', array(
			'fields' => array('Evaluation.classroom_id'),
			'conditions' => array(
				'Evaluation.id' => $id_evaluation
			),
			'recursive' => -1
		));

		$pupils = $this->Pupil->EvaluationsPupil->find('all', array(
			'conditions' => array(
				'EvaluationsPupil.evaluation_id' => $id_evaluation
			),
			'recursive' => -1,
			'fields' => array('Pupil.id','Pupil.first_name','Pupil.name','Level.id','Level.title'),
			'joins' => array(
				array('table' => 'pupils',
					'alias' => 'Pupil',
					'type' => 'INNER',
					'conditions' => array(
						'Pupil.id = EvaluationsPupil.pupil_id',
					),
				),
				array('table' => 'classrooms_pupils',
					'alias' => 'ClassroomsPupil',
					'type' => 'INNER',
					'conditions' => array(
						'ClassroomsPupil.pupil_id = EvaluationsPupil.pupil_id',
						'ClassroomsPupil.classroom_id = '.$evaluation['Evaluation']['classroom_id']
					),
				),
				array('table' => 'levels',
					'alias' => 'Level',
					'type' => 'INNER',
					'conditions' => array(
						'Level.id = ClassroomsPupil.level_id',
					),
				)
			),
			'order' => array(
				'Level.id', 'Pupil.name', 'Pupil.first_name'
			)
		));

		foreach($pupils as $pupil){
			$pupilsLevels[$pupil['Level']['id']]['title'] = $pupil['Level']['title'];
			$pupilsLevels[$pupil['Level']['id']]['pupils'][$pupil['Pupil']['id']] = $pupil['Pupil']['first_name'].' '.$pupil['Pupil']['name'];
		}

		return $pupilsLevels;
	}

    function searchIfAutogeneratedTestExists($classroom_id, $period_id){
        return $this->find('first', array(
            'fields' => array('Evaluation.id'),
            'conditions' => array(
                'Evaluation.classroom_id' => $classroom_id,
                'Evaluation.period_id' => $period_id,
                'Evaluation.unrated' => 1
            ),
        ));
    }

	function connectedUserIsOwnerOrAdmin($id_evaluation){
		$evaluation = $this->find('first',['conditions'=>['Evaluation.id' => $id_evaluation], 'recursive' => -1]);
		return $evaluation['Evaluation']['user_id'] == AuthComponent::user('id') || AuthComponent::user('role') == 'admin';
	}

	function itemBelongsToEvaluation($id_evaluation, $id_item){
		if ($this->EvaluationsItem->hasAny([
			'EvaluationsItem.evaluation_id' => $id_evaluation,
			'EvaluationsItem.item_id' => $id_item
		])){
			return true;
		}else{
			return false;
		}
	}

	function levelBelongsToClassroom($id_evaluation, $id_level){
		$evaluation = $this->find('first',['conditions'=>['Evaluation.id' => $id_evaluation], 'recursive' => -1]);
		if($this->Classroom->ClassroomsPupil->hasAny([
			'ClassroomsPupil.classroom_id' => $evaluation['Evaluation']['classroom_id'],
			'ClassroomsPupil.level_id' => $id_level
		])){
			return true;
		}else{
			return false;
		}
	}

	function pupilBelongsToEvaluation($id_evaluation, $id_pupil){
		if($this->EvaluationsPupil->hasAny([
			'EvaluationsPupil.evaluation_id' => $id_evaluation,
			'EvaluationsPupil.pupil_id' => $id_pupil
		])){
			return true;
		}else{
			return false;
		}
	}

    function autoGenerateTestForUnratedItems($classroom_id, $period_id){
        $data = array(
            'Evaluation' => array(
                'title' => 'Autogenerated test for unrated items',
                'classroom_id' => $classroom_id,
                'user_id' => AuthComponent::user('id'),
                'period_id' => $period_id,
                'unrated' => 1
            )
        );

        $this->create();
        $this->save($data,false);

        return $this->id;
    }

	function beforeValidate($options = array()) {
	  if (!isset($this->data['Pupil']['Pupil'])
	  || empty($this->data['Pupil']['Pupil'])) {
	    $this->invalidate('Pupil'); // fake validation error on Item
	    $this->Pupil->invalidate('Pupil', 'Sélectionnez au moins un élève !');
	  }
	  return true;
	}

}
