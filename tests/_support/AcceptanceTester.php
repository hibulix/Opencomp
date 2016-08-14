<?php
use Cake\ORM\TableRegistry;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    /**
     *
     */
    public function register()
    {
        $I = $this;

        //Register a new user through user creation account form.
        $I->amOnPage('/users/register');
        $I->fillField('username', 'admin');
        $I->fillField('email', 'test@example.org');
        $I->fillField('password', 'p455w0rd');
        $I->fillField('password_confirm', 'p455w0rd');
        $I->fillField('first_name', 'Test');
        $I->fillField('last_name', 'User');
        $I->checkOption('#tos');
        $I->click('Submit');
        $I->seeInDatabase('users', ['username' => 'admin']);

        //We promote user as admin and validate their account.
        $users = TableRegistry::get('Users');
        $user = $users->find('all', ['conditions' => ['username' => 'admin']])->first();
        $user->active = 1;
        $user->is_superuser = 1;
        $users->save($user);

        //Asserts that user has been successfully promoted admin.
        $I->seeInDatabase('users', ['username' => 'admin', 'active' => 1, 'is_superuser' => 1]);
    }

    public function login()
    {
        $this->register();

        $I = $this;

        $I->amOnPage('/');
        $I->fillField('username', 'admin');
        $I->fillField('password', 'p455w0rd');
        $I->click('Login');
        $I->see('Tableau');
    }
}
