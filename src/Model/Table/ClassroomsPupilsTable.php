<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ClassroomsPupils Model
 * @property LevelsTable Levels
 * @property ClassroomsTable Classrooms
 */
class ClassroomsPupilsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('classrooms_pupils');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->belongsTo('Classrooms', [
            'foreignKey' => 'classroom_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Pupils', [
            'foreignKey' => 'pupil_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Levels', [
            'foreignKey' => 'level_id',
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
            ->allowEmpty('id', 'create');

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
        $rules->add($rules->existsIn(['pupil_id'], 'Pupils'));
        $rules->add($rules->existsIn(['level_id'], 'Levels'));
        return $rules;
    }

    public function returnPupilsWithLevelsForClassroom($id){
        return $this->find('all', array(
            'conditions' => array('ClassroomsPupils.classroom_id' => $id),
            'fields' => array('ClassroomsPupils.id','Pupils.id','Pupils.first_name','Pupils.name','Pupils.sex','Pupils.birthday','Levels.title'),
            'order' => array('Pupils.name','Pupils.first_name'),
            'join' => array(
                array('table' => 'levels',
                    'alias' => 'Levels',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Levels.id = ClassroomsPupils.level_id',
                    ),
                ),
                array('table' => 'pupils',
                    'alias' => 'Pupils',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Pupils.id = ClassroomsPupils.pupil_id',
                    ),
                )
            )
        ));
    }
}
