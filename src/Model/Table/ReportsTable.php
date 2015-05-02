<?php
namespace App\Model\Table;

use App\Model\Entity\Report;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Reports Model
 */
class ReportsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('reports');
        $this->displayField('title');
        $this->primaryKey('id');
        $this->belongsTo('Classrooms', [
            'foreignKey' => 'classroom_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Periods', [
            'foreignKey' => 'period_id',
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
            ->requirePresence('title', 'create')
            ->notEmpty('title')
            ->requirePresence('header', 'create')
            ->notEmpty('header')
            ->requirePresence('footer', 'create')
            ->notEmpty('footer')
            ->allowEmpty('page_break')
            ->add('duplex_printing', 'valid', ['rule' => 'boolean'])
            ->requirePresence('duplex_printing', 'create')
            ->notEmpty('duplex_printing');

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
        $rules->add($rules->existsIn(['period_id'], 'Periods'));
        return $rules;
    }
}
