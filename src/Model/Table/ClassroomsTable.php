<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Classrooms Model
 */
class ClassroomsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('classrooms');
        $this->displayField('title');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('User', [
            'className' => 'Users',
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Years', [
            'foreignKey' => 'year_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Establishments', [
            'foreignKey' => 'establishment_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('CompetencesUsers', [
            'foreignKey' => 'classroom_id'
        ]);
        $this->hasMany('Evaluations', [
            'foreignKey' => 'classroom_id'
        ]);
        $this->hasMany('Items', [
            'foreignKey' => 'classroom_id'
        ]);
        $this->hasMany('Reports', [
            'foreignKey' => 'classroom_id'
        ]);
        $this->belongsToMany('Pupils', [
            'through' => 'ClassroomsPupils',
            'foreignKey' => 'classroom_id',
            'targetForeignKey' => 'pupil_id',
            'joinTable' => 'classrooms_pupils'
        ]);
        $this->belongsToMany('Users', [
            'foreignKey' => 'classroom_id',
            'targetForeignKey' => 'user_id',
            'joinTable' => 'classrooms_users'
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
            ->notEmpty('title');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['year_id'], 'Years'));
        $rules->add($rules->existsIn(['establishment_id'], 'Establishments'));
        return $rules;
    }
}
