<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Lpcnode Entity.
 */
class Lpcnode extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'parent_id' => true,
        'lft' => true,
        'rght' => true,
        'title' => true,
        'code' => true,
        'parent_lpcnode' => true,
        'items' => true,
        'child_lpcnodes' => true,
    ];
}
