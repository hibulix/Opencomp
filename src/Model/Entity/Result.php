<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Result Entity.
 */
class Result extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'evaluation_id' => true,
        'pupil_id' => true,
        'item_id' => true,
        'result' => true,
        'grade_a' => true,
        'grade_b' => true,
        'grade_c' => true,
        'grade_d' => true,
        'evaluation' => true,
        'pupil' => true,
        'item' => true,
    ];
}
