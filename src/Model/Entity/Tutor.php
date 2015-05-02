<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Tutor Entity.
 */
class Tutor extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'name' => true,
        'first_name' => true,
        'address' => true,
        'postcode' => true,
        'town' => true,
        'tel' => true,
        'tel2' => true,
        'email' => true,
        'notes' => true,
        'pupils' => true,
    ];
}
