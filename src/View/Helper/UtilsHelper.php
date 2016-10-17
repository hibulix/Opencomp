<?php
namespace App\View\Helper;

use App\View\Helper\AppHelper;

class UtilsHelper extends AppHelper
{
   
    /**
     * sortingSign function.
     * Permet de retourner un signe ascendant, descendant ou les deux en fonction
     * du tri actuellement appliqué par le paginateur.
     *
     * @access public
     * @param string $column Le nom de la propriété du modèle à tester
     * @param mixed $sortKey Utilisé pour transmettre $this->Paginator->sortKey()
     * @param mixed $sortDir Utilisé pour transmettre $this->Paginator->sortDir()
     * @return void
     */
    public function sortingSign($column, $sortKey, $sortDir)
    {
        if ($column == $sortKey) {
            if ($sortDir == 'asc') {
                echo '<i class="fa fa-sort-up"></i> ';
            } elseif ($sortDir == 'desc') {
                echo '<i class="fa fa-sort-down"></i> ';
            }
        } else {
            echo '<i class="fa fa-sort"></i> ';
        }
    }

    /**
     * @param int $qty Quantity value
     * @param int $total Total (sum)
     * @return string
     */
    public function getPercentValue($qty, $total)
    {
        return number_format($qty * 100 / $total, 3);
    }

    /**
     * Format levels of pupils that has taken an evaluation
     * @param object $evaluation Cake Data Object
     * @return string
     */
    public function getLevels($evaluation)
    {
        $levels = array_column($evaluation->toArray()['pupils'], 'levels');
        $levels = array_column($levels, 0);
        $levelsTitles = array_unique(array_column($levels, 'title'));

        $return = '';

        foreach ($levelsTitles as $level) {
            $return .= '<span class="label label-default">' . $level . '</span> ';
        }

        return $return;
    }
}
