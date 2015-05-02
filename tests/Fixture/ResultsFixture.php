<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ResultsFixture
 *
 */
class ResultsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'evaluation_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'pupil_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'item_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'result' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => 'NE', 'comment' => '', 'precision' => null],
        'grade_a' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'grade_b' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => true, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'grade_c' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => true, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'grade_d' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => true, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'updated' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'evaluation_id' => ['type' => 'index', 'columns' => ['evaluation_id'], 'length' => []],
            'pupil_id' => ['type' => 'index', 'columns' => ['pupil_id'], 'length' => []],
            'item_id' => ['type' => 'index', 'columns' => ['item_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'results_ibfk_5' => ['type' => 'foreign', 'columns' => ['evaluation_id'], 'references' => ['evaluations', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'results_ibfk_6' => ['type' => 'foreign', 'columns' => ['pupil_id'], 'references' => ['pupils', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
            'results_ibfk_7' => ['type' => 'foreign', 'columns' => ['item_id'], 'references' => ['items', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
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
            'pupil_id' => 1,
            'item_id' => 1,
            'result' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'grade_a' => 1,
            'grade_b' => 1,
            'grade_c' => 1,
            'grade_d' => 1,
            'created' => '2015-05-01 15:47:04',
            'updated' => '2015-05-01 15:47:04'
        ],
    ];
}
