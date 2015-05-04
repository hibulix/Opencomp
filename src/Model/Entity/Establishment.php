<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Establishment Entity.
 */
class Establishment extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        'address' => true,
        'postcode' => true,
        'town' => true,
        'user_id' => true,
        'academy_id' => true,
        'period_id' => true,
        'users' => true,
        'academy' => true,
        'current_period' => true,
        'classrooms' => true,
        'items' => true,
        'periods' => true,
    ];
}
