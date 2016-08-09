<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Levels Model
 */
class LevelsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('levels');
        $this->displayField('title');
        $this->primaryKey('id');
        $this->belongsTo('Cycles', [
            'foreignKey' => 'cycle_id'
        ]);
        $this->hasMany('ClassroomsPupils', [
            'foreignKey' => 'level_id'
        ]);
        $this->belongsToMany('Items', [
            'foreignKey' => 'level_id',
            'targetForeignKey' => 'item_id',
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
        $rules->add($rules->existsIn(['cycle_id'], 'Cycles'));
        

        return $rules;
    }
}
