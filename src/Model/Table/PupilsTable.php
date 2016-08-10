<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Pupils Model
 * @property ClassroomsPupilsTable $ClassroomsPupils
 * @property ClassroomsTable $Classrooms
 */
class PupilsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('pupils');
        $this->displayField('name');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Tutors', [
            'foreignKey' => 'tutor_id'
        ]);
        $this->hasMany('Results', [
            'foreignKey' => 'pupil_id'
        ]);
        $this->belongsToMany('Classrooms', [
            'through' => 'ClassroomsPupils',
        ]);
        $this->hasMany('ClassroomsPupils', [
            'className' => 'ClassroomsPupils',
            'foreignKey' => 'pupil_id',
        ]);
        $this->belongsToMany('Levels', [
            'foreignKey' => 'pupil_id',
            'targetForeignKey' => 'level_id',
            'joinTable' => 'classrooms_pupils'
        ]);
        $this->belongsToMany('Evaluations', [
            'foreignKey' => 'pupil_id',
            'targetForeignKey' => 'evaluation_id',
            'joinTable' => 'evaluations_pupils'
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
            ->requirePresence('sex', 'create')
            ->notEmpty('sex')
            ->add('birthday', 'valid', ['rule' => 'date'])
            ->requirePresence('birthday', 'create')
            ->notEmpty('birthday')
            ->add('state', 'valid', ['rule' => 'boolean'])
            ->allowEmpty('state');

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
        $rules->add($rules->existsIn(['tutor_id'], 'Tutors'));


        return $rules;
    }

    public function isUploadedFile($params)
    {
        if ((isset($params['error']) && $params['error'] == 0) ||
            (!empty($params['tmp_name']) && $params['tmp_name'] != 'none')
        ) {
            return is_uploaded_file($params['tmp_name']);
        }


        return false;
    }
}
