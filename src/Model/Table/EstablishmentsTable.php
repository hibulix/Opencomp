<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Establishments Model
 *
 * @property UsersTable Users
 * @property TownsTable Towns
 * @property \App\Model\Table\PeriodsTable Periods
 * @property \App\Model\Table\AcademiesTable Academies
 */
class EstablishmentsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('establishments');
        $this->displayField('name');
        $this->primaryKey('id');
        $this->belongsTo('Towns', [
            'foreignKey' => 'town_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Periods', [
            'foreignKey' => 'period_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Classrooms', [
            'foreignKey' => 'establishment_id'
        ]);
        $this->hasMany('Items', [
            'foreignKey' => 'establishment_id'
        ]);
        $this->hasMany('Periods', [
            'foreignKey' => 'establishment_id'
        ]);
        $this->belongsToMany('Users', [
            'foreignKey' => 'establishment_id',
            'targetForeignKey' => 'user_id',
            'joinTable' => 'establishments_users'
        ]);

        // Add the behaviour to your table
        $this->addBehavior('Search.Search');

        // Setup search filter using search manager
        $this->searchManager()
            // Here we will alias the 'q' query param to search the `Articles.title`
            // field and the `Articles.content` field, using a LIKE match, with `%`
            // both before and after.
            ->add('uai', 'Search.Like', [
                'before' => false,
                'after' => false,
                'mode' => 'or',
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'wildcardOne' => '?',
                'field' => [$this->aliasField('id')]
            ])
            ->add('n', 'Search.Like', [
                'before' => true,
                'after' => true,
                'mode' => 'or',
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'wildcardOne' => '?',
                'field' => [$this->aliasField('name')]
            ])
            ->add('s', 'Search.Like', [
                'before' => true,
                'after' => true,
                'mode' => 'or',
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'wildcardOne' => '?',
                'field' => [$this->aliasField('sector')]
            ])
            ->add('t', 'Search.Like', [
                'before' => true,
                'after' => true,
                'mode' => 'or',
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'wildcardOne' => '?',
                'field' => ['Towns.name']
            ])
            ->add('a', 'Search.Like', [
                'before' => true,
                'after' => true,
                'mode' => 'or',
                'comparison' => 'LIKE',
                'wildcardAny' => '*',
                'wildcardOne' => '?',
                'field' => ['Academies.name']
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
            ->add('id', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table'
            ])
            ->allowEmpty('id', 'create')
            ->requirePresence('name', 'create')
            ->notEmpty('name')
            ->requirePresence('address', 'create')
            ->notEmpty('address')
            ->add('postcode', 'valid', ['rule' => 'numeric'])
            ->requirePresence('postcode', 'create')
            ->notEmpty('postcode')
            ->requirePresence('town', 'create')
            ->notEmpty('town');

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
        return $rules;
    }

    public function getStats($idEstablishment, $currentYear)
    {
        return [
            'nbClassrooms' => $this->getNbClassrooms($idEstablishment, $currentYear),
            'nbPeriods' => $this->getNbPeriods($idEstablishment, $currentYear),
            'nbPupils' => $this->getNbPupils($idEstablishment, $currentYear)
        ];
    }

    private function getNbClassrooms($idEstablishment, $year)
    {
        $query = $this->Classrooms->find();
        $total = $query->matching('Establishments', function ($q) use ($idEstablishment, $year) {
            return $q->where([
                'Establishments.id' => $idEstablishment,
                'Classrooms.year_id' => $year->value
            ]);
        })->count();
        

        return $total;
    }

    private function getNbPupils($idEstablishment, $year)
    {
        $query = $this->Classrooms->Pupils->find();
        $total = $query->matching('Classrooms.Establishments', function ($q) use ($idEstablishment, $year) {
            return $q->where([
                'Establishments.id' => $idEstablishment,
                'Classrooms.year_id' => $year->value
            ]);
        })->count();
        

        return $total;
    }

    private function getNbPeriods($idEstablishment, $year)
    {
        $query = $this->Periods->find();
        $total = $query->matching('Establishments', function ($q) use ($idEstablishment, $year) {
            return $q->where([
                'Establishments.id' => $idEstablishment,
                'year_id' => $year->value
            ]);
        })->count();
        

        return $total;
    }
}
