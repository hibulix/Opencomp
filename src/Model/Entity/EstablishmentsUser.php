<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EstablishmentsUser Entity.
 */
class EstablishmentsUser extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'user_id' => true,
        'establishment_id' => true,
        'user' => true,
        'establishment' => true,
    ];
}
