<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

class EncodingComponent extends Component
{
    public function convertArrayToUtf8($array)
    {
        /** @noinspection PhpUnusedParameterInspection */
        array_walk_recursive($array, function (&$item, $key) {
            if (!mb_detect_encoding($item, 'utf-8', true)) {
                $item = utf8_encode($item);
            }
        });

        return $array;
    }
}
