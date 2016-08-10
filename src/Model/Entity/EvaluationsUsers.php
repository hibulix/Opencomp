<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EvaluationsItem Entity.
 */
class EvaluationsUsers extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'evaluation_id' => true,
        'user_id' => true,
        'evaluation' => true,
        'user' => true,
    ];
}
