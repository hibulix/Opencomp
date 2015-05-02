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
            ->add('unrated', 'valid', ['rule' => 'boolean'])
            ->requirePresence('unrated', 'create')
            ->notEmpty('unrated');

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
}
