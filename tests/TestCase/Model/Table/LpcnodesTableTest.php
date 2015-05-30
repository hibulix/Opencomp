<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LpcnodesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LpcnodesTable Test Case
 */
class LpcnodesTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.lpcnodes',
        'app.items',
        'app.competences',
        'app.users',
        'app.competences_users',
        'app.classrooms',
        'app.classrooms_users',
        'app.years',
        'app.establishments',
        'app.establishments_users',
        'app.academies',
        'app.academies_users',
        'app.current_periods',
        'app.periods',
        'app.evaluations',
        'app.results',
        'app.evaluations_items',
        'app.pupils',
        'app.evaluations_pupils',
        'app.reports',
        'app.classrooms_pupils',
        'app.levels',
        'app.cycles',
        'app.items_levels'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Lpcnodes') ? [] : ['className' => 'App\Model\Table\LpcnodesTable'];
        $this->Lpcnodes = TableRegistry::get('Lpcnodes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Lpcnodes);

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
