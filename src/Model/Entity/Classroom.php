<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Classroom Entity.
 */
class Classroom extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'title' => true,
        'user_id' => true,
        'year_id' => true,
        'establishment_id' => true,
        'users' => true,
        'year' => true,
        'establishment' => true,
        'competences_users' => true,
        'evaluations' => true,
        'items' => true,
        'reports' => true,
        'pupils' => true,
    ];
}
