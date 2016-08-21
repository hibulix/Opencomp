<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Classrooms Model
 *
 * @property EvaluationsTable Evaluations
 * @property UsersTable Users
 * @property EstablishmentsTable Establishments
 * @property ClassroomsPupilsTable ClassroomsPupils
 */
class ClassroomsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('classrooms');
        $this->displayField('title');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Years', [
            'foreignKey' => 'year_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Establishments', [
            'foreignKey' => 'establishment_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('CompetencesUsers', [
            'foreignKey' => 'classroom_id'
        ]);
        $this->hasMany('Evaluations', [
            'foreignKey' => 'classroom_id'
        ]);
        $this->hasMany('Competences', [
            'foreignKey' => 'classroom_id'
        ]);
        $this->hasMany('Reports', [
            'foreignKey' => 'classroom_id'
        ]);
        $this->hasMany('ClassroomsPupils', [
            'foreignKey' => 'classroom_id'
        ]);
        $this->belongsToMany('Pupils', [
            'through' => 'ClassroomsPupils',
            'foreignKey' => 'classroom_id',
            'targetForeignKey' => 'pupil_id',
            'joinTable' => 'classrooms_pupils'
        ]);
        $this->belongsToMany('Users', [
            'foreignKey' => 'classroom_id',
            'targetForeignKey' => 'user_id',
            'joinTable' => 'classrooms_users'
        ]);

        // Add the behaviour to your table
        $this->addBehavior('Search.Search');

        // Setup search filter using search manager
        $this->searchManager()
            // Here we will alias the 'q' query param to search the `Articles.title`
            // field and the `Articles.content` field, using a LIKE match, with `%`
            // both before and after.
            ->value('Evaluations.period_id');
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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['year_id'], 'Years'));
        $rules->add($rules->existsIn(['establishment_id'], 'Establishments'));
        

        return $rules;
    }

    /**
     * @param int $idClassroom Classroom identifier
     * @return array
     */
    public function findPupilsByLevelsInClassroom($idClassroom)
    {
        $pupilsLevelsSrc = $this->ClassroomsPupils
                              ->find()
                              ->select(['Pupils.id', 'Pupils.first_name', 'Pupils.name', 'Pupils.sex', 'Pupils.birthday', 'Levels.id', 'Levels.title'])
                              ->where(['ClassroomsPupils.classroom_id' => $idClassroom])
                              ->innerJoinWith('Pupils')
                              ->innerJoinWith('Levels')
                              ->orderAsc('Pupils.name, Pupils.first_name');

        $pupilsLevels = [];
        foreach ($pupilsLevelsSrc as $pupilLevel) {
            $pupilsLevels[$pupilLevel->_matchingData['Levels']->id][$pupilLevel->_matchingData['Levels']->title][$pupilLevel->_matchingData['Pupils']->id]['first_name'] = $pupilLevel->_matchingData['Pupils']->first_name;
            $pupilsLevels[$pupilLevel->_matchingData['Levels']->id][$pupilLevel->_matchingData['Levels']->title][$pupilLevel->_matchingData['Pupils']->id]['name'] = $pupilLevel->_matchingData['Pupils']->name;
            $pupilsLevels[$pupilLevel->_matchingData['Levels']->id][$pupilLevel->_matchingData['Levels']->title][$pupilLevel->_matchingData['Pupils']->id]['sex'] = $pupilLevel->_matchingData['Pupils']->sex;
            $pupilsLevels[$pupilLevel->_matchingData['Levels']->id][$pupilLevel->_matchingData['Levels']->title][$pupilLevel->_matchingData['Pupils']->id]['birthday'] = $pupilLevel->_matchingData['Pupils']->birthday;
        }

        return $pupilsLevels;
    }

    /**
     * @param int $idClassroom Classroom identifier
     * @return $this|array
     */
    public function getPupilsSelect2($idClassroom)
    {
        $pupilsLevels = $this->ClassroomsPupils->Levels->find()
            ->select(['Levels.id', 'Levels.title'])
            ->distinct('Levels.id')
            ->matching('ClassroomsPupils', function (Query $q) use ($idClassroom) {
                return $q->where(['ClassroomsPupils.classroom_id' => $idClassroom]);
            })
            ->contain(['Pupils' => function (Query $q) use ($idClassroom) {
                return $q
                    ->select(['id', 'first_name', 'name'])
                    ->where(['ClassroomsPupils.classroom_id' => $idClassroom])
                    ->orderAsc('Pupils.name, Pupils.first_name');
            }])
            ->orderAsc('Levels.id');

        $res = [];
        foreach ($pupilsLevels as $pupilLevel) {
            $level = [
                'id' => $pupilLevel->id,
                'text' => $pupilLevel->title,
                'children' => []
            ];

            foreach ($pupilLevel->pupils as $pupil) {
                $formattedPupil = [
                    'id' => $pupil->id,
                    'text' => $pupil->first_name . " " . $pupil->name
                ];
                array_push($level['children'], $formattedPupil);
            }
            array_push($res, $level);
        }
        

        return $res;
    }
}
