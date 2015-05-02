<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Academy Entity.
 */
class Academy extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        'type' => true,
        'establishments' => true,
        'users' => true,
    ];
}
