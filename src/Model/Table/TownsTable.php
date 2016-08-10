<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;


/**
 * Towns Model
 *
 * @property AcademiesTable Academies
 */
class TownsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('towns');
        $this->displayField('name');
        $this->primaryKey('id');
        $this->belongsTo('Academies', [
            'className' => 'Academies',
            'foreignKey' => 'academy_id',
            'joinType' => 'INNER'
        ]);

        // Add the behaviour to your table
        $this->addBehavior('Search.Search');

        // Setup search filter using search manager
        $this->searchManager()
            // Here we will alias the 'q' query param to search the `Articles.title`
            // field and the `Articles.content` field, using a LIKE match, with `%`
            // both before and after.
            ->add('search', 'Search.Like', [
                'before' => false,
                'after' => true,
                'mode' => 'or',
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'wildcardOne' => '?',
                'field' => [$this->aliasField('name')]
            ]);
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
        $rules->add($rules->existsIn(['academy_id'], 'Academies'));
        return $rules;
    }
}
