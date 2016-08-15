<?php
namespace App\Model\Table;

use Cake\Datasource\ConnectionManager;
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
     * Méthode intermédiaire permettant de retourner les bornes gauches et droites
     * d'une compétence à partir son identifiant primaire.
     *
     * @param array $idsArray Un tableau contentant les id_competence dont on souhaite récupérer les bornes.
     * @return array|null Un tableau avec en clés les bornes gauches et en valeurs les bornes droites.
     */
    private function returnBoundsFromCompetenceId($idsArray)
    {
        return $this->find('list', [
            'keyField' => 'lft',
            'valueField' => 'rght',
            'conditions' => [
                'Competences.id IN' => $idsArray
            ]
        ])->hydrate(false)->toArray();
    }

    /**
     * Méthode permettant de retourner l'ensemble de la hiérarchie de compétences
     * à partir des id_competence les plus profonds uniquements.
     *
     * @param array $idsArray Un tableau contentant les id_competence les plus profond dont on souhaite la hiérarchie.
     * @param string $format Format to return
     * @return array Un tableau prêt à être JSONifié pour passer à JsTree.
     */
    public function findAllCompetencesFromCompetenceId($idsArray, $format = 'jstree')
    {
        $competenceBounds = $this->returnBoundsFromCompetenceId($idsArray);
        $sqlString = "SELECT * FROM competences AS Competences WHERE ";

        if (count($idsArray) > 1) {
            $idCompetences = implode(',', $idsArray);
        } else {
            $idCompetences = $idsArray;
        }

        $sqlString .= "id IN ( $idCompetences ) OR ";
        foreach ($competenceBounds as $lft => $rght) {
            $sqlString .= "(lft < $lft AND rght > $rght) OR ";
        }
        $sqlString = substr($sqlString, 0, -3);
        $sqlString .= "ORDER BY lft;";
        $competences = ConnectionManager::get('default')->execute($sqlString)->fetchAll('assoc');
        if ($format == 'jstree') {
            return $this->formatCompetencesTheJstreeWay($competences);
        } else {
            return $this->formatTreeHelperWay($competences);
        }
    }

    /**
     * Méthode permettant de retourner l'ensemble de l'arbre de compétences.
     *
     * @return array Un tableau prêt à être JSONifié pour passer à JsTree.
     */
    public function findAllCompetences()
    {
        $competences = $this->find('all', [
            'order' => ['Competences.lft']
        ]);


        return $this->formatCompetencesTheJstreeWay($competences);
    }

    /**
     * Méthode permettant de formatter un tableau en vue de son passage à JsTree.
     *
     * @param array $dataset Un resultset CakePHP contenant plusieurs objets Competence.
     * @return array Un tableau prêt à être JSONifié pour passer à JsTree.
     */
    private function formatCompetencesTheJstreeWay($dataset)
    {
        $tab = [];
        foreach ($dataset as $num => $c) {
            //Si c'est un tableau, on le converti en objet
            if (is_array($c)) {
                $c = json_decode(json_encode($c), false);
            }

            $tab[$num]['id'] = $c->id;
            if ($c->parent_id) {
                $tab[$num]['parent'] = $c->parent_id;
            } else {
                $tab[$num]['parent'] = "#";
            }
            $tab[$num]['icon'] = 'fa fa-lg fa-cubes';
            $tab[$num]['text'] = $c->title;
            $tab[$num]['data']['type'] = "noeud";
            $tab[$num]['li_attr']['data-type'] = "noeud";
            $tab[$num]['li_attr']['data-id'] = $c->id;
        }


        return $tab;
    }

    /**
     * Méthode permettant de formatter un tableau en émulant le renvoie de la méthode GenerateTreeListWithDepth.
     *
     * @param array $dataset Un resultset CakePHP contenant plusieurs tableaux Competence.
     * @return array Un tableau en émulant le format de renvoie de la méthode GenerateTreeListWithDepth
     */
    private function formatTreeHelperWay($dataset)
    {
        $tab = [];
        foreach ($dataset as $num => $c) {
            $tab[$num]['id'] = $c['id'];
            $tab[$num]['title'] = $c['title'];
            $tab[$num]['depth'] = $c['depth'];
        }


        return $tab;
    }
}
