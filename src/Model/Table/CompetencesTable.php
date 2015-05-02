<?php
namespace App\Model\Table;

use App\Model\Entity\Competence;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Competences Model
 */
class CompetencesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('competences');
        $this->displayField('title');
        $this->primaryKey('id');
        $this->addBehavior('Tree');
        $this->belongsTo('ParentCompetences', [
            'className' => 'Competences',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildCompetences', [
            'className' => 'Competences',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('Items', [
            'foreignKey' => 'competence_id'
        ]);
        $this->belongsToMany('Users', [
            'foreignKey' => 'competence_id',
            'targetForeignKey' => 'user_id',
            'joinTable' => 'competences_users'
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
            ->add('depth', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('depth')
            ->add('lft', 'valid', ['rule' => 'numeric'])
            ->requirePresence('lft', 'create')
            ->notEmpty('lft')
            ->add('rght', 'valid', ['rule' => 'numeric'])
            ->requirePresence('rght', 'create')
            ->notEmpty('rght')
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
        $rules->add($rules->existsIn(['parent_id'], 'ParentCompetences'));
        return $rules;
    }

    /**
     * Méthode permettant de retourner l'ensemble de l'arbre de compétences.
     *
     * @return array Un tableau prêt à être JSONifié pour passer à JsTree.
     */
    public function findAllCompetences(){
        $competences = $this->find('all',[
            'order' => ['Competences.lft']
        ]);
        return $this->formatCompetencesTheJstreeWay($competences);
    }

    /**
     * Méthode permettant de formatter un tableau en vue de son passage à JsTree.
     *
     * @param array $dataset Un resultset CakePHP contenant plusieurs tableaux Competence.
     * @return array Un tableau prêt à être JSONifié pour passer à JsTree.
     */
    private function formatCompetencesTheJstreeWay($dataset){
        $tab = array();
        foreach($dataset as $num=>$c){
            $tab[$num]['id'] = $c->id;
            if($c->parent_id)
                $tab[$num]['parent'] = $c->parent_id;
            else
                $tab[$num]['parent'] = "#";
            $tab[$num]['icon'] = 'fa fa-lg fa-cubes';
            $tab[$num]['text'] = $c->title;
            $tab[$num]['data']['type'] = "noeud";
            $tab[$num]['li_attr']['data-type'] = "noeud";
            $tab[$num]['li_attr']['data-id'] = $c->id;
        }
        return $tab;
    }
}
