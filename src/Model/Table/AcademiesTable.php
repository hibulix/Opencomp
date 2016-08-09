<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Academies Model
 *
 * @property UsersTable Users
 */
class AcademiesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('academies');
        $this->displayField('name');
        $this->primaryKey('id');
        $this->hasMany('Establishments', [
            'foreignKey' => 'academy_id'
        ]);
        $this->belongsToMany('Users', [
            'foreignKey' => 'academy_id',
            'targetForeignKey' => 'user_id',
            'joinTable' => 'academies_users'
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
            ->add('type', 'valid', ['rule' => 'boolean'])
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        return $validator;
    }
}
