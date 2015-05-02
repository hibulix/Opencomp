<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Item Entity.
 */
class Item extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'title' => true,
        'competence_id' => true,
        'type' => true,
        'user_id' => true,
        'classroom_id' => true,
        'lpcnode_id' => true,
        'establishment_id' => true,
        'competence' => true,
        'user' => true,
        'classroom' => true,
        'lpcnode' => true,
        'establishment' => true,
        'results' => true,
        'evaluations' => true,
        'levels' => true,
    ];
}
