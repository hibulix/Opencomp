<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Tutors Model
 */
class TutorsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('tutors');
        $this->displayField('name');
        $this->primaryKey('id');
        $this->hasMany('Pupils', [
            'foreignKey' => 'tutor_id'
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
            ->requirePresence('first_name', 'create')
            ->notEmpty('first_name')
            ->requirePresence('address', 'create')
            ->notEmpty('address')
            ->add('postcode', 'valid', ['rule' => 'numeric'])
            ->requirePresence('postcode', 'create')
            ->notEmpty('postcode')
            ->requirePresence('town', 'create')
            ->notEmpty('town')
            ->add('tel', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('tel')
            ->add('tel2', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('tel2')
            ->add('email', 'valid', ['rule' => 'email'])
            ->allowEmpty('email')
            ->allowEmpty('notes');

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
        $rules->add($rules->isUnique(['email']));
        

        return $rules;
    }
}
