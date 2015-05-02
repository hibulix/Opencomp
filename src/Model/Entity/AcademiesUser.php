<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AcademiesUser Entity.
 */
class AcademiesUser extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'academy_id' => true,
        'user_id' => true,
        'academy' => true,
        'user' => true,
    ];
}
