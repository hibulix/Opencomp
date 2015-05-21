<?php
namespace App\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\YearsTable Test Case
 */
class YearsTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.years',
        'app.classrooms',
        'app.users',
        'app.classrooms_users',
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
        'app.reports',
        'app.evaluations_items',
        'app.pupils',
        'app.tutors',
        'app.classrooms_pupils',
        'app.levels',
        'app.cycles',
        'app.items_levels',
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
        $config = TableRegistry::exists('Years') ? [] : ['className' => 'App\Model\Table\YearsTable'];
        $this->Years = TableRegistry::get('Years', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Years);

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
}
