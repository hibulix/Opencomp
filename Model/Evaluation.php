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
			'notempty' => array(
				'rule' => array('notempty'),
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
			 )
		));
		
		foreach($pupils as $pupil){
			$pupilsLevels[$pupil['Level']['title']][$pupil['Pupil']['id']] = $pupil['Pupil']['first_name'].' '.$pupil['Pupil']['name'];				
		}
		
		return $pupilsLevels;
	}
	
	function resultsForAnEvaluation($id_evaluation){
		$result = $this->Result->find('all', array(
			'fields' => array('result', 'count(result) as nbresult'),
			'conditions' => array(
				'evaluation_id' => $id_evaluation
			),
			'group' => array('result'),
		));
		
		$resultats['A'] = 0;
		$resultats['B'] = 0;
		$resultats['C'] = 0;
		$resultats['D'] = 0;
		$resultats['ABS'] = 0;
		
		foreach($result as $infos){
			$resultats[$infos['Result']['result']] = intval($infos['0']['nbresult']);
		}
		
		$resultats['TOT'] = $resultats['A'] + $resultats['B'] + $resultats['C'] + $resultats['D'];
		if($resultats['TOT'] != 0){
			$resultats['pourcent_A'] = $resultats['A'] * 100 / $resultats['TOT'];
			$resultats['pourcent_B'] = $resultats['B'] * 100 / $resultats['TOT'];
			$resultats['pourcent_C'] = $resultats['C'] * 100 / $resultats['TOT'];
			$resultats['pourcent_D'] = $resultats['D'] * 100 / $resultats['TOT'];
			
			return $resultats;
		}else{
			$resultats['pourcent_A'] = 0;
			$resultats['pourcent_B'] = 0;
			$resultats['pourcent_C'] = 0;
			$resultats['pourcent_D'] = 0;
			
			return $resultats;
		}
		
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
