<?php
namespace App\Model\Table;

use App\Model\Entity\Lpcnode;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Lpcnodes Model
 */
class LpcnodesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('lpcnodes');
        $this->displayField('title');
        $this->primaryKey('id');
        $this->addBehavior('Tree');
        $this->belongsTo('ParentLpcnodes', [
            'className' => 'Lpcnodes',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('Items', [
            'foreignKey' => 'lpcnode_id'
        ]);
        $this->hasMany('ChildLpcnodes', [
            'className' => 'Lpcnodes',
            'foreignKey' => 'parent_id'
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
            ->add('lft', 'valid', ['rule' => 'numeric'])
            ->requirePresence('lft', 'create')
            ->notEmpty('lft')
            ->add('rght', 'valid', ['rule' => 'numeric'])
            ->requirePresence('rght', 'create')
            ->notEmpty('rght')
            ->requirePresence('title', 'create')
            ->notEmpty('title')
            ->requirePresence('code', 'create')
            ->notEmpty('code');

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
        $rules->add($rules->existsIn(['parent_id'], 'ParentLpcnodes'));
        return $rules;
    }
}
