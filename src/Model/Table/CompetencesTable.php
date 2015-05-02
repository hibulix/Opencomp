<?php
namespace App\Model\Table;

use App\Model\Entity\Competence;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Competences Model
 */
class CompetencesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('competences');
        $this->displayField('title');
        $this->primaryKey('id');
        $this->addBehavior('Tree');
        $this->belongsTo('ParentCompetences', [
            'className' => 'Competences',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildCompetences', [
            'className' => 'Competences',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('Items', [
            'foreignKey' => 'competence_id'
        ]);
        $this->belongsToMany('Users', [
            'foreignKey' => 'competence_id',
            'targetForeignKey' => 'user_id',
            'joinTable' => 'competences_users'
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
            ->add('depth', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('depth')
            ->add('lft', 'valid', ['rule' => 'numeric'])
            ->requirePresence('lft', 'create')
            ->notEmpty('lft')
            ->add('rght', 'valid', ['rule' => 'numeric'])
            ->requirePresence('rght', 'create')
            ->notEmpty('rght')
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
        $rules->add($rules->existsIn(['parent_id'], 'ParentCompetences'));
        return $rules;
    }
}
