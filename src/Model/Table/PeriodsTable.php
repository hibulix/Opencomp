<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Periods Model
 */
class PeriodsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('periods');
        $this->displayField('well_named');
        $this->primaryKey('id');
        $this->belongsTo('Years', [
            'foreignKey' => 'year_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Establishments', [
            'foreignKey' => 'establishment_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Evaluations', [
            'foreignKey' => 'period_id'
        ]);
        $this->hasMany('Reports', [
            'foreignKey' => 'period_id'
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
            ->add('begin', 'valid', ['rule' => 'date'])
            ->requirePresence('begin', 'create')
            ->notEmpty('begin')
            ->add('end', 'valid', ['rule' => 'date'])
            ->requirePresence('end', 'create')
            ->notEmpty('end');

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
        $rules->add($rules->existsIn(['year_id'], 'Years'));
        $rules->add($rules->existsIn(['establishment_id'], 'Establishments'));
        

        return $rules;
    }
}
