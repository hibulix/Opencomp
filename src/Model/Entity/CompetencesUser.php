<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * CompetencesUser Entity.
 */
class CompetencesUser extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'competence_id' => true,
        'user_id' => true,
        'classroom_id' => true,
        'competence' => true,
        'user' => true,
        'classroom' => true,
    ];
}
