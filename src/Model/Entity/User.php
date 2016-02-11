<?php
namespace App\Model\Entity;

use Cake\Auth\WeakPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity.
 */
class User extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'username' => true,
        'password' => true,
        'first_name' => true,
        'name' => true,
        'email' => true,
        'role' => true,
        'yubikeyID' => true,
        'classrooms' => true,
        'establishments' => true,
        'evaluations' => true,
        'items' => true,
        'academies' => true,
        'competences' => true,
    ];

    protected function _getFullName()
    {
        return  $this->_properties['first_name'] . ' ' .
                $this->_properties['name'];
    }

    /**
     * @param $password
     * @return string
     */
    protected function _setPassword($password)
    {
        return (new WeakPasswordHasher())->hash($password);
    }
}
