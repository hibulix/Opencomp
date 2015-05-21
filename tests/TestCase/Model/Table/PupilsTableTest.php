<?php
namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PupilsTable Test Case
 */
class PupilsTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.pupils',
        'app.tutors',
        'app.results',
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
        'app.evaluations',
        'app.periods',
        'app.reports',
        'app.evaluations_items',
        'app.evaluations_pupils',
        'app.levels',
        'app.cycles',
        'app.classrooms_pupils',
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
        $config = TableRegistry::exists('Pupils') ? [] : ['className' => 'App\Model\Table\PupilsTable'];
        $this->Pupils = TableRegistry::get('Pupils', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Pupils);

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
