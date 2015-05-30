<?php
namespace App\Model\Table;

use App\Model\Entity\Item;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\Event;
use App\Model\Table\ArrayObject;

/**
 * Items Model
 */
class ItemsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('items');
        $this->displayField('title');
        $this->primaryKey('id');
        $this->belongsTo('Competences', [
            'foreignKey' => 'competence_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsTo('Classrooms', [
            'foreignKey' => 'classroom_id'
        ]);
        $this->belongsTo('Lpcnodes', [
            'foreignKey' => 'lpcnode_id'
        ]);
        $this->belongsTo('Establishments', [
            'foreignKey' => 'establishment_id'
        ]);
        $this->hasMany('Results', [
            'foreignKey' => 'item_id'
        ]);
        $this->belongsToMany('Evaluations', [
            'foreignKey' => 'item_id',
            'targetForeignKey' => 'evaluation_id',
            'joinTable' => 'evaluations_items'
        ]);
        $this->belongsToMany('Levels', [
            'foreignKey' => 'item_id',
            'targetForeignKey' => 'level_id',
            'joinTable' => 'items_levels'
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
            ->requirePresence('title', 'create')
            ->notEmpty('title')
            ->add('type', 'valid', ['rule' => 'numeric'])
            ->requirePresence('type', 'create')
            ->notEmpty('type')
            ->add('levels', 'valid', ['rule' => ['multiple', ['min'=>1]]])
            ->requirePresence('levels', 'create')
            ->notEmpty('levels');

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
        $rules->add($rules->existsIn(['competence_id'], 'Competences'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['classroom_id'], 'Classrooms'));
        $rules->add($rules->existsIn(['lpcnode_id'], 'Lpcnodes'));
        $rules->add($rules->existsIn(['establishment_id'], 'Establishments'));
        return $rules;
    }

    function findAllItems($item_ids = null, $user_id){
        if(isset($item_ids))
            $conditions['Items.id IN'] = $item_ids;
        else{
            $conditions['OR']['Items.user_id'] = $user_id;
            $conditions['OR']['Items.type IN'] = ['1','2'];
        }
        $items = $this->find('all',[
            'conditions' => $conditions,
            'contain' => 'Levels'
        ]);
        $tab = array();
        $num = 0;
        foreach($items as $item){
            $tab[$num]['id'] = 'item-'.$item->id;
            $tab[$num]['parent'] = $item->competence_id;
            $tab[$num]['icon'] = 'fa fa-lg fa-cube ' . $this->returnItemClassType($item);
            $tab[$num]['text'] =  $this->returnLpcLink($item) . $this->returnFormattedLevelsItem($item) . $item->title;
            $tab[$num]['data']['type'] = "feuille";
            $tab[$num]['data']['id'] = $item->id;
            $tab[$num]['li_attr']['data-type'] = "feuille";
            $tab[$num]['li_attr']['data-id'] = $item->id;
            $num++;
        }
        return $tab;
    }

    private function returnFormattedLevelsItem($item){
        $string = '';
        foreach($item->levels as $level){
            $string .= sprintf('<span class="label label-default">%s</span> ',$level->title);
        }
        return $string;
    }

    private function returnLpcLink($item){
        if(isset($item->lpcnode_id)){
            return '<span title="Cet item est jumelé avec le LPC" class="info label label-success"><i class="fa fa-link"></i></span> ';
        }else{
            return '<span title="Cet item n\'est pas jumelé avec le LPC" class="info label label-danger"><i class="fa fa-unlink"></i></span> ';
        }
    }

    private function returnItemClassType($item){
        switch ($item->type) {
            case 1:
                return "text-danger";
                break;
            case 2:
                return "text-info";
                break;
            case 3:
                return "text-success";
                break;
        }
    }
}
