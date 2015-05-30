<?php
namespace App\Model\Table;

use App\Model\Entity\Evaluation;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * Evaluations Model
 */
class EvaluationsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('evaluations');
        $this->displayField('title');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Classrooms', [
            'foreignKey' => 'classroom_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Periods', [
            'foreignKey' => 'period_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Results', [
            'foreignKey' => 'evaluation_id'
        ]);
        $this->belongsToMany('Items', [
            'foreignKey' => 'evaluation_id',
            'targetForeignKey' => 'item_id',
            'joinTable' => 'evaluations_items'
        ]);
        $this->belongsToMany('Pupils', [
            'foreignKey' => 'evaluation_id',
            'targetForeignKey' => 'pupil_id',
            'joinTable' => 'evaluations_pupils'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create')
            ->requirePresence('title', 'create')
            ->notEmpty('title')
            ->add('unrated', 'valid', ['rule' => 'boolean']);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['classroom_id'], 'Classrooms'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['period_id'], 'Periods'));
        return $rules;
    }


    public function findPupilsByLevelsInClassroom($id_classroom){
        $levelsTable = TableRegistry::get('Levels');
		$levels = $levelsTable->find('all', array(
			'conditions' => array(
				'ClassroomsPupils.classroom_id' => $id_classroom
			),
			'fields' => 'ClassroomsPupils.level_id',
			'join' => array(
			    array('table' => 'classrooms_pupils',
			        'alias' => 'ClassroomsPupils',
			        'type' => 'LEFT',
			        'conditions' => array(
			            'Levels.id = ClassroomsPupils.level_id',
			        ),
			    )
			 )
		))->hydrate(false)->extract('ClassroomsPupils.level_id')->toArray();

        $classroomsPupilsTable = TableRegistry::get('ClassroomsPupils');
		$pupils = $classroomsPupilsTable->find('all', array(
			'conditions' => array(
				'ClassroomsPupils.classroom_id' => $id_classroom,
				'ClassroomsPupils.level_id IN' => array_unique($levels)
			),
			'fields' => array('Pupils.id','Pupils.first_name','Pupils.name','Levels.title'),
			'join' => array(
			    array('table' => 'pupils',
			        'type' => 'LEFT',
			        'conditions' => array(
			            'Pupils.id = ClassroomsPupils.pupil_id',
			        ),
			    ),
			    array('table' => 'levels',
			        'type' => 'LEFT',
			        'conditions' => array(
			            'Levels.id = ClassroomsPupils.level_id',
			        ),
			    )
			 )
		));

		foreach($pupils as $pupil){
			$pupilsLevels[$pupil->Levels['title']][$pupil->Pupils['id']] = $pupil->Pupils['first_name'].' '.$pupil->Pupils['name'];
		}

		return $pupilsLevels;
	}

    function resultsForAnEvaluation($id_evaluation){
        $result = $this->Results->find();
        $result = $this->Results->find('all', array(
            'fields' => array('result','count' => $result->func()->count('result')),
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
            $resultats[$infos->result] = intval($infos->count);
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

    public function findItemsByPosition($evaluation_id){
        $items = $this->find('all', array(
            'join' => array(
                array('table' => 'evaluations_items',
                    'alias' => 'EvaluationsItems',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Evaluations.id = EvaluationsItems.evaluation_id',
                    )
                ),
                array('table' => 'items',
                    'alias' => 'Items',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'EvaluationsItems.item_id = Items.id',
                    )
                )
            ),
            'fields' => array('EvaluationsItems.position','Items.title','Items.id', 'Evaluations.title'),
            'conditions' => array('Evaluations.id' => $evaluation_id),
            'order' => array('EvaluationsItems.position'),
        ));
        return $items;
    }
}
