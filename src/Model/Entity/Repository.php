<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Repository Entity.
 */
class Repository extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'title' => true,
        'legislation' => true,
        'color' => true,
        'deleted' => true
    ];
}
