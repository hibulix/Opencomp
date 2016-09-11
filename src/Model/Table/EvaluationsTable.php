<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Evaluations Model
 *
 * @property PupilsTable $Pupils
 * @property EvaluationsPupilsTable $EvaluationsPupils
 * @property ClassroomsTable $Classrooms
 * @property EvaluationsCompetencesTable $EvaluationsCompetences
 * @property ResultsTable $Results
 * @property PeriodsTable Periods
 */
class EvaluationsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('evaluations');
        $this->displayField('title');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Classrooms', [
            'foreignKey' => 'classroom_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Periods', [
            'foreignKey' => 'period_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Results', [
            'foreignKey' => 'evaluation_id'
        ]);
        $this->hasMany('EvaluationsCompetences', [
            'foreignKey' => 'evaluation_id'
        ]);
        $this->hasMany('EvaluationsPupils', [
            'foreignKey' => 'evaluation_id'
        ]);
        $this->belongsToMany('Competences', [
            'foreignKey' => 'evaluation_id',
            'targetForeignKey' => 'competence_id',
            'joinTable' => 'evaluations_competences'
        ]);
        $this->belongsToMany('Pupils', [
            'foreignKey' => 'evaluation_id',
            'targetForeignKey' => 'pupil_id',
            'joinTable' => 'evaluations_pupils'
        ]);
        $this->belongsToMany('Users', [
            'foreignKey' => 'evaluation_id',
            'targetForeignKey' => 'user_id',
            'joinTable' => 'evaluations_users'
        ]);

        // Add the behaviour to your table
        $this->addBehavior('Search.Search');

        // Setup search filter using search manager
        $this->searchManager()
            // Here we will alias the 'q' query param to search the `Articles.title`
            // field and the `Articles.content` field, using a LIKE match, with `%`
            // both before and after.
            ->value('period_id');
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
            ->add('unrated', 'valid', ['rule' => 'boolean'])
            ->add('pupils', [
                'notEmpty' => [
                    'rule' => ['multiple', ['min' => 1]],
                    'message' => 'Vous devez sélectionner au moins un élève.'
                ]
            ]);

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
        $rules->add($rules->existsIn(['users'], 'Users'));
        $rules->add($rules->existsIn(['period_id'], 'Periods'));
        $rules->add(function ($entity, $options) {
            $classroomsPupils = TableRegistry::get('ClassroomsPupils');

            return $classroomsPupils->isSameCycle($entity);
        }, 'sameCycle', [
            'errorField' => 'pupils',
            'message' => 'Seuls des élèves de même cycle peuvent passer la même évaluation.'
        ]);


        return $rules;
    }

    /**
     * @param int $idEvaluation Evaluation id
     * @return mixed
     */
    public function resultsForAnEvaluation($idEvaluation)
    {
        $result = $this->Results->find();
        $result = $this->Results->find('all', [
            'fields' => ['result', 'count' => $result->func()->count('result')],
            'conditions' => [
                'evaluation_id' => $idEvaluation
            ],
            'group' => ['result'],
        ]);

        $resultats['A'] = 0;
        $resultats['B'] = 0;
        $resultats['C'] = 0;
        $resultats['D'] = 0;
        $resultats['ABS'] = 0;


        foreach ($result as $infos) {
            $resultats[$infos->result] = intval($infos->count);
        }

        $resultats['TOT'] = $resultats['A'] + $resultats['B'] + $resultats['C'] + $resultats['D'];
        if ($resultats['TOT'] != 0) {
            $resultats['pourcent_A'] = $resultats['A'] * 100 / $resultats['TOT'];
            $resultats['pourcent_B'] = $resultats['B'] * 100 / $resultats['TOT'];
            $resultats['pourcent_C'] = $resultats['C'] * 100 / $resultats['TOT'];
            $resultats['pourcent_D'] = $resultats['D'] * 100 / $resultats['TOT'];

            return $resultats;
        } else {
            $resultats['pourcent_A'] = 0;
            $resultats['pourcent_B'] = 0;
            $resultats['pourcent_C'] = 0;
            $resultats['pourcent_D'] = 0;

            return $resultats;
        }
    }

    /**
     * @param int $evaluationId Evaluation identifier
     * @return \Cake\ORM\Query
     */
    public function findCompetencesByPosition($evaluationId)
    {
        $competences = $this->find('all', [
            'join' => [
                ['table' => 'evaluations_competences',
                    'alias' => 'EvaluationsCompetences',
                    'type' => 'LEFT',
                    'conditions' => [
                        'Evaluations.id = EvaluationsCompetences.evaluation_id',
                    ]
                ],
                ['table' => 'competences',
                    'alias' => 'Competences',
                    'type' => 'LEFT',
                    'conditions' => [
                        'EvaluationsCompetences.competence_id = Competences.id',
                    ]
                ]
            ],
            'fields' => ['EvaluationsCompetences.position', 'Competences.title', 'Competences.id', 'Evaluations.title'],
            'conditions' => ['Evaluations.id' => $evaluationId],
            'order' => ['EvaluationsCompetences.position'],
        ]);


        return $competences;
    }

    /**
     * @param int $idEvaluation Evaluation identifier
     * @return array
     */
    public function findPupilsByLevels($idEvaluation)
    {
        $pupilsLevelsSrc = $this->EvaluationsPupils
            ->find()
            ->select(['Pupils.id', 'Pupils.first_name', 'Pupils.name', 'Pupils.sex', 'Pupils.birthday', 'Levels.id', 'Levels.title'])
            ->where(['EvaluationsPupils.evaluation_id' => $idEvaluation])
            ->innerJoinWith('Pupils.Levels')
            ->orderAsc('Pupils.name, Pupils.first_name');

        $pupilsLevels = [];
        foreach ($pupilsLevelsSrc as $pupilLevel) {
            $pupilsLevels[$pupilLevel->_matchingData['Levels']->id][$pupilLevel->_matchingData['Levels']->title][$pupilLevel->_matchingData['Pupils']->id] = $pupilLevel->_matchingData['Pupils'];
        }


        return $pupilsLevels;
    }

    /**
     * @param int $idEvaluation Evaluation identifier
     * @return array
     */
    public function getCompetencesThatBelongsToEvaluation($idEvaluation)
    {
        return $this->EvaluationsCompetences->find('list', [
            'keyField' => 'id',
            'valueField' => 'competence_id'
        ])
            ->where([
            'EvaluationsCompetences.evaluation_id' => $idEvaluation
            ])->toArray();
    }

    /**
     * @param int $idEvaluation Evaluation identifier
     * @param int $idItem Item identifier
     * @return bool
     */
    public function itemBelongsToEvaluation($idEvaluation, $idItem)
    {
        if ($this->EvaluationsCompetences->find()->where([
            'EvaluationsCompetences.evaluation_id' => $idEvaluation,
            'EvaluationsCompetences.competence_id' => $idItem
        ])->isEmpty()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param int $idEvaluation Evaluation identifier
     * @param int $idLevel Level identifier
     * @return bool
     */
    public function levelBelongsToClassroom($idEvaluation, $idLevel)
    {
        $evaluation = $this->get($idEvaluation);
        if ($this->Classrooms->ClassroomsPupils->find()->where([
            'ClassroomsPupils.classroom_id' => $evaluation->classroom_id,
            'ClassroomsPupils.level_id' => $idLevel
        ])->isEmpty()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param int $idEvaluation Evaluation identifier
     * @param int $idPupil Pupil identifier
     * @return bool
     */
    public function pupilBelongsToEvaluation($idEvaluation, $idPupil)
    {
        if ($this->EvaluationsPupils->find()->where([
            'EvaluationsPupils.evaluation_id' => $idEvaluation,
            'EvaluationsPupils.pupil_id' => $idPupil
        ])->isEmpty()) {
            return false;
        } else {
            return true;
        }
    }
}
