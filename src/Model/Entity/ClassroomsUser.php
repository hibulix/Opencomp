<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ClassroomsUser Entity.
 */
class ClassroomsUser extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'user_id' => true,
        'classroom_id' => true,
        'user' => true,
        'classroom' => true,
    ];
}
