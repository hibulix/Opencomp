<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Pupil Entity.
 */
class Pupil extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        'first_name' => true,
        'sex' => true,
        'birthday' => true,
        'state' => true,
        'tutor_id' => true,
        'tutor' => true,
        'results' => true,
        'classrooms' => true,
        'evaluations' => true,
    ];

    protected function _getFullName()
    {
        return  $this->_properties['first_name'] . ' ' .
        $this->_properties['name'];
    }
}
