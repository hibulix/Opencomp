<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Period Entity.
 */
class Period extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'begin' => true,
        'end' => true,
        'year_id' => true,
        'establishment_id' => true,
        'year' => true,
        'establishment' => true,
        'evaluations' => true,
        'reports' => true,
    ];
}
