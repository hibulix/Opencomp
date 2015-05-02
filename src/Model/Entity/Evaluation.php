<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Evaluation Entity.
 */
class Evaluation extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'title' => true,
        'classroom_id' => true,
        'user_id' => true,
        'period_id' => true,
        'unrated' => true,
        'classroom' => true,
        'user' => true,
        'period' => true,
        'results' => true,
        'items' => true,
        'pupils' => true,
    ];
}