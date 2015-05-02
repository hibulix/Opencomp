<?php
namespace App\Model\Table;

use App\Model\Entity\Evaluation;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

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
}
