<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Results Model
 */
class ResultsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('results');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Evaluations', [
            'foreignKey' => 'evaluation_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Pupils', [
            'foreignKey' => 'pupil_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Items', [
            'foreignKey' => 'item_id',
            'joinType' => 'INNER'
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
            ->requirePresence('result', 'create')
            ->notEmpty('result')
            ->add('grade_a', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('grade_a')
            ->add('grade_b', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('grade_b')
            ->add('grade_c', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('grade_c')
            ->add('grade_d', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('grade_d');

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
        $rules->add($rules->existsIn(['evaluation_id'], 'Evaluations'));
        $rules->add($rules->existsIn(['pupil_id'], 'Pupils'));
        $rules->add($rules->existsIn(['item_id'], 'Items'));
        return $rules;
    }

    public function findResultsForReport($pupil_id, $classroom_id, $period_id){
        return $this->find('all', array(
            'fields' => array(
                'Items.title',
                'Items.competence_id',
                'Pupils.id',
                'Pupils.name',
                'Pupils.first_name',
                'result'
            ),
            'conditions' => array(
                'Pupils.id' => $pupil_id,
                'Evaluations.period_id IN' => explode(',',$period_id),
                'Evaluations.classroom_id' => $classroom_id
            ),
            'contain' => array(
                'Items',
                'Pupils',
                'Evaluations.Periods',
                'Evaluations.Classrooms'
            )
        ));
    }
}
