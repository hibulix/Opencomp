<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Establishment Entity.
 */
class Establishment extends Entity
{
    use GeolocationTrait;

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        'main_naming' => true,
        'uai_patronym' => true,
        'address' => true,
        'town_id' => true,
        'locality' => true,
        'users' => true,
        'academy' => true,
        'classrooms' => true,
        'items' => true,
        'periods' => true,
        'X' => true,
        'Y' => true
    ];
}
