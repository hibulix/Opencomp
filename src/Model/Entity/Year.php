<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Year Entity.
 */
class Year extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'title' => true,
        'classrooms' => true,
        'periods' => true,
    ];
}
