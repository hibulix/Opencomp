<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ClassroomsPupilsFixture
 *
 */
class ClassroomsPupilsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'classroom_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'pupil_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'level_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'classroom_id' => ['type' => 'index', 'columns' => ['classroom_id'], 'length' => []],
            'pupil_id' => ['type' => 'index', 'columns' => ['pupil_id'], 'length' => []],
            'level_id' => ['type' => 'index', 'columns' => ['level_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'classrooms_pupils_ibfk_4' => ['type' => 'foreign', 'columns' => ['classroom_id'], 'references' => ['classrooms', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
            'classrooms_pupils_ibfk_5' => ['type' => 'foreign', 'columns' => ['pupil_id'], 'references' => ['pupils', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
            'classrooms_pupils_ibfk_6' => ['type' => 'foreign', 'columns' => ['level_id'], 'references' => ['levels', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
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
            'classroom_id' => 1,
            'pupil_id' => 1,
            'level_id' => 1
        ],
    ];
}
