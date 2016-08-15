<?php
namespace App\Model\Table;

use Cake\Database\Expression\QueryExpression;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * EvaluationsCompetences Model
 */
class EvaluationsCompetencesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('evaluations_competences');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->belongsTo('Evaluations', [
            'foreignKey' => 'evaluation_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Competences', [
            'foreignKey' => 'competence_id',
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
            ->add('position', 'valid', ['rule' => 'numeric'])
            ->requirePresence('position', 'create')
            ->notEmpty('position');

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
        $rules->add($rules->existsIn(['competence_id'], 'Items'));
        

        return $rules;
    }

    /**
     * @param int $evaluationId Evaluation identifier
     * @param int $competenceId Item identifier
     * @return mixed
     */
    public function isCompetenceAlreadyAttachedToEvaluation($evaluationId, $competenceId)
    {
        return $this->find('all', [
            'conditions' => [
                'EvaluationsCompetences.evaluation_id' => $evaluationId,
                'EvaluationsCompetences.competence_id' => $competenceId
            ]
        ])->first();
    }

    /**
     * Cette fonction permet de renuméroter la position des items associés
     * à une évaluation (par exemple après la dissociation d'un item).
     * @param int $evaluationId L'id de l'évaluation concerné par l'opération
     * @param int $position À partir de quel position faut il renuméroter ?
     * @return mixed
     */
    public function renumberItemsEvaluation($evaluationId, $position)
    {
        return $this->updateAll(
            [new QueryExpression('evaluations_competences.position = evaluations_competences.position - 1')],
            ['evaluations_competences.evaluation_id' => $evaluationId,
                'evaluations_competences.position >' => $position]
        );
    }
}
