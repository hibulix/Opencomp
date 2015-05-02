<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ClassroomsPupil Entity.
 */
class ClassroomsPupil extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'classroom_id' => true,
        'pupil_id' => true,
        'level_id' => true,
        'classroom' => true,
        'pupil' => true,
        'level' => true,
    ];
}
