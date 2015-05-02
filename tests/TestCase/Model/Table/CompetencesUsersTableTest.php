<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CompetencesUsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CompetencesUsersTable Test Case
 */
class CompetencesUsersTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.competences_users',
        'app.competences',
        'app.items',
        'app.users',
        'app.classrooms',
        'app.classrooms_users',
        'app.years',
        'app.establishments',
        'app.evaluations',
        'app.reports',
        'app.pupils',
        'app.classrooms_pupils',
        'app.levels'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('CompetencesUsers') ? [] : ['className' => 'App\Model\Table\CompetencesUsersTable'];
        $this->CompetencesUsers = TableRegistry::get('CompetencesUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CompetencesUsers);

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
