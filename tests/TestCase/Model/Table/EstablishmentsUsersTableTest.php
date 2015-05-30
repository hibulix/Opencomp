<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EstablishmentsUsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EstablishmentsUsersTable Test Case
 */
class EstablishmentsUsersTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.establishments_users',
        'app.users',
        'app.establishments',
        'app.academies',
        'app.academies_users',
        'app.current_periods',
        'app.classrooms',
        'app.classrooms_users',
        'app.years',
        'app.competences_users',
        'app.competences',
        'app.items',
        'app.evaluations',
        'app.reports',
        'app.pupils',
        'app.classrooms_pupils',
        'app.levels',
        'app.periods'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('EstablishmentsUsers') ? [] : ['className' => 'App\Model\Table\EstablishmentsUsersTable'];
        $this->EstablishmentsUsers = TableRegistry::get('EstablishmentsUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EstablishmentsUsers);

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
