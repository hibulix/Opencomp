<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EvaluationsPupilsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EvaluationsPupilsTable Test Case
 */
class EvaluationsPupilsTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.evaluations_pupils',
        'app.evaluations',
        'app.classrooms',
        'app.users',
        'app.classrooms_users',
        'app.years',
        'app.establishments',
        'app.establishments_users',
        'app.academies',
        'app.academies_users',
        'app.current_periods',
        'app.items',
        'app.periods',
        'app.competences_users',
        'app.competences',
        'app.reports',
        'app.pupils',
        'app.classrooms_pupils',
        'app.levels',
        'app.results',
        'app.evaluations_items'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EvaluationsPupils') ? [] : ['className' => 'App\Model\Table\EvaluationsPupilsTable'];
        $this->EvaluationsPupils = TableRegistry::get('EvaluationsPupils', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EvaluationsPupils);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
