<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * EvaluationsPupilsFixture
 *
 */
class EvaluationsPupilsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'evaluation_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'pupil_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'evaluation_id' => ['type' => 'index', 'columns' => ['evaluation_id'], 'length' => []],
            'pupil_id' => ['type' => 'index', 'columns' => ['pupil_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'evaluations_pupils_ibfk_3' => ['type' => 'foreign', 'columns' => ['evaluation_id'], 'references' => ['evaluations', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'evaluations_pupils_ibfk_4' => ['type' => 'foreign', 'columns' => ['pupil_id'], 'references' => ['pupils', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
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
            'evaluation_id' => 1,
            'pupil_id' => 1
        ],
    ];
}
