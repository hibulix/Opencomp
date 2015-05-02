<?php
namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 */
class UsersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('users');
        $this->displayField('name');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->hasMany('Classrooms', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Establishments', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Evaluations', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Items', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsToMany('Academies', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'academy_id',
            'joinTable' => 'academies_users'
        ]);
        $this->belongsToMany('Classrooms', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'classroom_id',
            'joinTable' => 'classrooms_users'
        ]);
        $this->belongsToMany('Competences', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'competence_id',
            'joinTable' => 'competences_users'
        ]);
        $this->belongsToMany('Establishments', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'establishment_id',
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
            ->requirePresence('username', 'create')
            ->notEmpty('username')
            ->requirePresence('password', 'create')
            ->notEmpty('password')
            ->requirePresence('first_name', 'create')
            ->notEmpty('first_name')
            ->requirePresence('name', 'create')
            ->notEmpty('name')
            ->add('email', 'valid', ['rule' => 'email'])
            ->allowEmpty('email')
            ->requirePresence('role', 'create')
            ->notEmpty('role')
            ->allowEmpty('yubikeyID');

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
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['email']));
        return $rules;
    }
}
