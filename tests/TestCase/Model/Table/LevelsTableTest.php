<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LevelsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LevelsTable Test Case
 */
class LevelsTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.levels',
        'app.cycles',
        'app.classrooms_pupils',
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
        'app.competences',
        'app.competences_users',
        'app.lpcnodes',
        'app.results',
        'app.evaluations',
        'app.periods',
        'app.evaluations_items',
        'app.pupils',
        'app.evaluations_pupils',
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
        $config = TableRegistry::exists('Levels') ? [] : ['className' => 'App\Model\Table\LevelsTable'];
        $this->Levels = TableRegistry::get('Levels', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Levels);

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
