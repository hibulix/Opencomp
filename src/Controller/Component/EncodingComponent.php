<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

class EncodingComponent extends Component
{
    /**
     * @param array $array Array to convert to utf8 encoding
     * @return mixed
     */
    public function convertArrayToUtf8($array)
    {
        array_walk_recursive($array, function (&$item, $key) {
            if (!mb_detect_encoding($item, 'utf-8', true)) {
                $item = utf8_encode($item);
            }
        });

        return $array;
    }
}
