<?php
namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TutorsTable Test Case
 */
class TutorsTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.tutors',
        'app.pupils',
        'app.results',
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
        'app.competences',
        'app.competences_users',
        'app.lpcnodes',
        'app.evaluations_items',
        'app.levels',
        'app.cycles',
        'app.classrooms_pupils',
        'app.items_levels',
        'app.periods',
        'app.reports',
        'app.evaluations_pupils'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Tutors') ? [] : ['className' => 'App\Model\Table\TutorsTable'];
        $this->Tutors = TableRegistry::get('Tutors', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Tutors);

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
