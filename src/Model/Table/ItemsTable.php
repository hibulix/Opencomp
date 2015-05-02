<?php
namespace App\Model\Table;

use App\Model\Entity\Item;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Items Model
 */
class ItemsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('items');
        $this->displayField('title');
        $this->primaryKey('id');
        $this->belongsTo('Competences', [
            'foreignKey' => 'competence_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsTo('Classrooms', [
            'foreignKey' => 'classroom_id'
        ]);
        $this->belongsTo('Lpcnodes', [
            'foreignKey' => 'lpcnode_id'
        ]);
        $this->belongsTo('Establishments', [
            'foreignKey' => 'establishment_id'
        ]);
        $this->hasMany('Results', [
            'foreignKey' => 'item_id'
        ]);
        $this->belongsToMany('Evaluations', [
            'foreignKey' => 'item_id',
            'targetForeignKey' => 'evaluation_id',
            'joinTable' => 'evaluations_items'
        ]);
        $this->belongsToMany('Levels', [
            'foreignKey' => 'item_id',
            'targetForeignKey' => 'level_id',
            'joinTable' => 'items_levels'
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
            ->add('type', 'valid', ['rule' => 'numeric'])
            ->requirePresence('type', 'create')
            ->notEmpty('type');

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
        $rules->add($rules->existsIn(['competence_id'], 'Competences'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['classroom_id'], 'Classrooms'));
        $rules->add($rules->existsIn(['lpcnode_id'], 'Lpcnodes'));
        $rules->add($rules->existsIn(['establishment_id'], 'Establishments'));
        return $rules;
    }
}
