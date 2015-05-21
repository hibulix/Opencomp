<?php
namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PeriodsTable Test Case
 */
class PeriodsTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.periods',
        'app.years',
        'app.establishments',
        'app.users',
        'app.establishments_users',
        'app.academies',
        'app.academies_users',
        'app.current_periods',
        'app.classrooms',
        'app.classrooms_users',
        'app.competences_users',
        'app.competences',
        'app.items',
        'app.lpcnodes',
        'app.results',
        'app.evaluations',
        'app.evaluations_items',
        'app.pupils',
        'app.evaluations_pupils',
        'app.levels',
        'app.cycles',
        'app.classrooms_pupils',
        'app.items_levels',
        'app.reports'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Periods') ? [] : ['className' => 'App\Model\Table\PeriodsTable'];
        $this->Periods = TableRegistry::get('Periods', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Periods);

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
