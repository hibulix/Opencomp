<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use /** @noinspection PhpUnusedAliasInspection */
    Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Results Model
 *
 * @property EvaluationsTable $Evaluations
 * @property EvaluationsPupilsTable $EvaluationsPupils
 * @property EvaluationsCompetencesTable $EvaluationsCompetences
 */
class ResultsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('results');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Evaluations', [
            'foreignKey' => 'evaluation_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Pupils', [
            'foreignKey' => 'pupil_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Items', [
            'foreignKey' => 'item_id',
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
            ->allowEmpty('id', 'create')
            ->requirePresence('result', 'create')
            ->notEmpty('result')
            ->add('grade_a', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('grade_a')
            ->add('grade_b', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('grade_b')
            ->add('grade_c', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('grade_c')
            ->add('grade_d', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('grade_d');

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
        $rules->add($rules->existsIn(['evaluation_id'], 'Evaluations'));
        $rules->add($rules->existsIn(['pupil_id'], 'Pupils'));
        $rules->add($rules->existsIn(['item_id'], 'Items'));


        return $rules;
    }

    public function findResultsForReport($pupilId, $classroomId, $periodId)
    {
        return $this->find('all', [
            'fields' => [
                'Items.title',
                'Items.competence_id',
                'Pupils.id',
                'Pupils.name',
                'Pupils.first_name',
                'result'
            ],
            'conditions' => [
                'Pupils.id' => $pupilId,
                'Evaluations.period_id IN' => explode(',', $periodId),
                'Evaluations.classroom_id' => $classroomId
            ],
            'contain' => [
                'Items',
                'Pupils',
                'Evaluations.Periods',
                'Evaluations.Classrooms'
            ]
        ]);
    }

    /**
     * Function used to save multiple results at once for all pupils
     * associted with an evaluation_id for a specific item_id.
     *
     * @param int $evaluationId evaluation_id related to the results
     * @param int $itemId item_id related to the results
     * @param string $result result to save : A, B, C, D, NE or ABS
     *
     * @return boolean whether the results has been saved or not
     */
    public function saveGlobalResultEvaluationItem($evaluationId, $itemId, $result)
    {
        $this->deleteAll(['evaluation_id' => $evaluationId, 'item_id' => $itemId]);
        $pupils = $this->Evaluations->EvaluationsPupils->find('list', [
            'valueField' => 'pupil_id',
            'conditions' => ['evaluation_id' => $evaluationId]
        ]);

        $iteration = 0;
        $data = [];
        foreach ($pupils as $pupilId) {
            $data[$iteration]['evaluation_id'] = $evaluationId;
            $data[$iteration]['pupil_id'] = $pupilId;
            $data[$iteration]['item_id'] = $itemId;
            $data[$iteration]['result'] = $result;
            $data = $this->setResult($data, $iteration, $result);
            $iteration++;
        }

        $entities = $this->newEntities($data);

        return $this->connection()->transactional(function () use ($entities) {
            foreach ($entities as $entity) {
                $this->save($entity);
            }
        });
    }

    /**
     * Function used to save multiple results at once for all pupils
     * associted with an evaluation_id for a specific item_id.
     *
     * @param int $evaluationId evaluation_id related to the results
     * @param int $itemId item_id related to the results
     * @param $level_id
     * @param string $result result to save : A, B, C, D, NE or ABS
     * @return bool whether the results has been saved or not
     * @internal param mixed $parameter [description]
     */
    public function saveGlobalResultEvaluationItemLevel($evaluationId, $itemId, $levelId, $result)
    {
        $targetedPupils = $this->Evaluations->EvaluationsPupils->find()
            ->select('Pupils.id')
            ->innerJoinWith('Pupils.Levels')
            ->where([
                'EvaluationsPupils.evaluation_id' => $evaluationId,
                'ClassroomsPupils.level_id' => $levelId
            ]);

        $this->deleteAll(['Results.evaluation_id' => $evaluationId, 'Results.item_id' => $itemId, 'Results.pupil_id IN' => $targetedPupils]);

        $iteration = 0;
        $data = [];
        foreach ($targetedPupils->all() as $evaluationsPupils) {
            $data[$iteration]['evaluation_id'] = $evaluationId;
            $data[$iteration]['pupil_id'] = $evaluationsPupils->_matchingData['Pupils']->id;
            $data[$iteration]['item_id'] = $itemId;
            $data[$iteration]['result'] = $result;
            $data = $this->setResult($data, $iteration, $result);
            $iteration++;
        }
        $entities = $this->newEntities($data);

        return $this->connection()->transactional(function () use ($entities) {
            foreach ($entities as $entity) {
                $this->save($entity);
            }
        });
    }

    public function saveResultEvaluationItemPupil($evaluationId, $itemId, $pupilId, $result)
    {
        $this->deleteAll(['Results.evaluation_id' => $evaluationId, 'Results.item_id' => $itemId, 'Results.pupil_id' => $pupilId]);
        $data[0]['evaluation_id'] = $evaluationId;
        $data[0]['pupil_id'] = $pupilId;
        $data[0]['item_id'] = $itemId;
        $data[0]['result'] = $result;
        $data = $this->setResult($data, 0, $result);

        $entities = $this->newEntities($data);

        return $this->connection()->transactional(function () use ($entities) {
            foreach ($entities as $entity) {
                $this->save($entity);
            }
        });
    }

    public function saveGlobalResultEvaluationPupil($evaluationId, $pupilId, $result)
    {
        $this->deleteAll(['Results.evaluation_id' => $evaluationId, 'Results.pupil_id' => $pupilId]);
        $evaluationItems = $this->Evaluations->findItemsByPosition($evaluationId);

        $iteration = 0;
        foreach ($evaluationItems as $item) {
            $data[$iteration]['evaluation_id'] = $evaluationId;
            $data[$iteration]['pupil_id'] = $pupilId;
            $data[$iteration]['item_id'] = $item->Items['id'];
            $data[$iteration]['result'] = $result;
            $data = $this->setResult($data, $iteration, $result);
            $iteration++;
        }

        $entities = $this->newEntities($data);

        return $this->connection()->transactional(function () use ($entities) {
            foreach ($entities as $entity) {
                $this->save($entity);
            }
        });
    }

    private function setResult($data, $iteration, $grade)
    {
        switch ($grade) {
            case 'A':
                $data[$iteration]['grade_a'] = 1;
                break;
            case 'B':
                $data[$iteration]['grade_b'] = 1;
                break;
            case 'C':
                $data[$iteration]['grade_c'] = 1;
                break;
            case 'D':
                $data[$iteration]['grade_d'] = 1;
                break;
        }


        return $data;
    }
}
