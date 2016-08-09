<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EvaluationsItem Entity.
 */
class EvaluationsCompetence extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'evaluation_id' => true,
        'competence_id' => true,
        'position' => true,
        'evaluation' => true,
        'competence' => true,
    ];
}
