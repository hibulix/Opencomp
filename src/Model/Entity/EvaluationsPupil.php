<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EvaluationsPupil Entity.
 */
class EvaluationsPupil extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'evaluation_id' => true,
        'pupil_id' => true,
        'evaluation' => true,
        'pupil' => true,
    ];
}
