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

    protected function getWellNamed()
    {
        return 'du ' .
                $this->_properties['begin']->i18nFormat('dd/MM/YYYY') . ' au ' .
                $this->_properties['end']->i18nFormat('dd/MM/YYYY');
    }
}
