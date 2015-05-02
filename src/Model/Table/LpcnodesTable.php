<?php
namespace App\Model\Table;

use App\Model\Entity\Lpcnode;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Lpcnodes Model
 */
class LpcnodesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('lpcnodes');
        $this->displayField('title');
        $this->primaryKey('id');
        $this->addBehavior('Tree');
        $this->belongsTo('ParentLpcnodes', [
            'className' => 'Lpcnodes',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('Items', [
            'foreignKey' => 'lpcnode_id'
        ]);
        $this->hasMany('ChildLpcnodes', [
            'className' => 'Lpcnodes',
            'foreignKey' => 'parent_id'
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
            ->add('lft', 'valid', ['rule' => 'numeric'])
            ->requirePresence('lft', 'create')
            ->notEmpty('lft')
            ->add('rght', 'valid', ['rule' => 'numeric'])
            ->requirePresence('rght', 'create')
            ->notEmpty('rght')
            ->requirePresence('title', 'create')
            ->notEmpty('title')
            ->requirePresence('code', 'create')
            ->notEmpty('code');

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
        $rules->add($rules->existsIn(['parent_id'], 'ParentLpcnodes'));
        return $rules;
    }

    public function findAllNodesWithParentId($id){
        return $this->find('all',
            array(
                'contain' => array('ChildLpcnodes', 'Items'),
                'conditions' => array(
                    'Lpcnodes.parent_id'=>$id,
                    'Items.type' => 1
                ),
                'order' => 'Lpcnodes.lft ASC',
            )
        );
    }

    public function findAllLpcnodeIn($ids_array = null){
        if(isset($ids_array) && is_array($ids_array))
            $conditions['Lpcnodes.id'] = $ids_array;
        else
            $conditions = array();
        $lpcnodes = $this->find('all',[
            'contain' => ['ChildLpcnodes'],
            'conditions' => [
                $conditions
            ],
            'order' => ['Lpcnodes.lft'],
        ]);
        $tab = array();
        foreach($lpcnodes as $num=>$node){
            $tab[$num]['id'] = $node->id;
            if($node->parent_id)
                $tab[$num]['parent'] = $node->parent_id;
            else
                $tab[$num]['parent'] = "#";
            $tab[$num]['text'] = $node->title;
            $tab[$num]['li_attr']['data-id'] = $node->id;
            if(count($node['ChildLpcnode'])){
                $tab[$num]['icon'] = 'fa fa-lg fa-cubes';
                $tab[$num]['data']['type'] = "noeud";
                $tab[$num]['li_attr']['data-type'] = "noeud";
            }else{
                $tab[$num]['icon'] = 'fa fa-lg fa-cube text-danger';
                $tab[$num]['data']['type'] = "feuille";
                $tab[$num]['li_attr']['data-type'] = "feuille";
            }
        }
        return $tab;
    }
}
