<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Level Entity.
 */
class Level extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'title' => true,
        'cycle_id' => true,
        'cycle' => true,
        'classrooms_pupils' => true,
        'items' => true,
    ];
}
