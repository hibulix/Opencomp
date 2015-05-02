<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EvaluationsItem Entity.
 */
class EvaluationsItem extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'evaluation_id' => true,
        'item_id' => true,
        'position' => true,
        'evaluation' => true,
        'item' => true,
    ];
}
