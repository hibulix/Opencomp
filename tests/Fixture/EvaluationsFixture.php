<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EvaluationsFixture
 *
 */
class EvaluationsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'title' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'classroom_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'user_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'period_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'unrated' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => 'Booléen indiquant si cette évaluation est une évaluation factice servant uniquement à contenir des items travaillés mais non notés', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'updated' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'user_id' => ['type' => 'index', 'columns' => ['user_id'], 'length' => []],
            'period_id' => ['type' => 'index', 'columns' => ['period_id'], 'length' => []],
            'classroom_id' => ['type' => 'index', 'columns' => ['classroom_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'evaluations_ibfk_4' => ['type' => 'foreign', 'columns' => ['classroom_id'], 'references' => ['classrooms', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
            'evaluations_ibfk_5' => ['type' => 'foreign', 'columns' => ['user_id'], 'references' => ['users', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
            'evaluations_ibfk_6' => ['type' => 'foreign', 'columns' => ['period_id'], 'references' => ['periods', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
'engine' => 'InnoDB', 'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'title' => 'Lorem ipsum dolor sit amet',
            'classroom_id' => 1,
            'user_id' => 1,
            'period_id' => 1,
            'unrated' => 1,
            'created' => '2015-05-01 15:47:02',
            'updated' => '2015-05-01 15:47:02'
        ],
    ];
}
