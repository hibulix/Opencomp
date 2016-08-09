<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Report Entity.
 */
class Report extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'title' => true,
        'header' => true,
        'footer' => true,
        'page_break' => true,
        'classroom_id' => true,
        'period_id' => true,
        'duplex_printing' => true,
        'classroom' => true,
    ];
}
