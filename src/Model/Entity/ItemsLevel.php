<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ItemsLevel Entity.
 */
class ItemsLevel extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'item_id' => true,
        'level_id' => true,
        'item' => true,
        'level' => true,
    ];
}
