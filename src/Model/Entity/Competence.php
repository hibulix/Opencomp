<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Competence Entity.
 */
class Competence extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'parent_id' => true,
        'depth' => true,
        'lft' => true,
        'rght' => true,
        'title' => true,
        'parent_competence' => true,
        'child_competences' => true,
        'items' => true,
        'users' => true,
    ];
}
