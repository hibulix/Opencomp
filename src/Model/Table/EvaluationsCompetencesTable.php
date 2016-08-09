<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Database\Expression\QueryExpression;

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

    public function isItemAlreadyAttachedToEvaluation($evaluation_id, $item_id){
        return $this->find('all', array(
            'conditions' => array(
                'EvaluationsCompetences.evaluation_id' => $evaluation_id,
                'EvaluationsCompetences.competence_id' => $item_id
            )
        ))->first();
    }

    /**
     * Cette fonction permet de renuméroter la position des items associés
     * à une évaluation (par exemple après la dissociation d'un item).
     * @param int $evaluation_id L'id de l'évaluation concerné par l'opération
     * @param int $position À partir de quel position faut il renuméroter ?
     * @return mixed
     */
    public function renumberItemsEvaluation($evaluation_id, $position){
        return $this->updateAll(
            array(new QueryExpression('evaluations_competences.position = evaluations_competences.position - 1')),
            array('evaluations_competences.evaluation_id' => $evaluation_id,
                'evaluations_competences.position >' => $position)
        );
    }
}
