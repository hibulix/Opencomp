<?php
namespace App\Model\Table;

use App\Model\Entity\Establishment;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Establishments Model
 */
class EstablishmentsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('establishments');
        $this->displayField('name');
        $this->primaryKey('id');
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Academies', [
            'foreignKey' => 'academy_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('CurrentPeriods', [
            'foreignKey' => 'current_period_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Classrooms', [
            'foreignKey' => 'establishment_id'
        ]);
        $this->hasMany('Items', [
            'foreignKey' => 'establishment_id'
        ]);
        $this->hasMany('Periods', [
            'foreignKey' => 'establishment_id'
        ]);
        $this->belongsToMany('Users', [
            'foreignKey' => 'establishment_id',
            'targetForeignKey' => 'user_id',
            'joinTable' => 'establishments_users'
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
            ->requirePresence('name', 'create')
            ->notEmpty('name')
            ->requirePresence('address', 'create')
            ->notEmpty('address')
            ->add('postcode', 'valid', ['rule' => 'numeric'])
            ->requirePresence('postcode', 'create')
            ->notEmpty('postcode')
            ->requirePresence('town', 'create')
            ->notEmpty('town');

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
        $rules->add($rules->existsIn(['academy_id'], 'Academies'));
        $rules->add($rules->existsIn(['current_period_id'], 'CurrentPeriods'));
        return $rules;
    }
}
