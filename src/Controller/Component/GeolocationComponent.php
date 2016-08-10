<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

class GeolocationComponent extends Component
{

    function lambert93ToWgs84($x, $y){
        $x = number_format($x, 10, '.', '');
        $y = number_format($y, 10, '.', '');
        $b7  = 298.257222101;
        $b8  = 1/$b7;
        $b9  = 2*$b8-$b8*$b8;
        $b10 = sqrt($b9);
        $b13 = 3.000000000;
        $b14 = 700000.0000;
        $b15 = 12655612.0499;
        $b16 = 0.7256077650532670;
        $b17 = 11754255.426096;
        $delx = $x - $b14;
        $dely = $y - $b15;
        $gamma = atan( -($delx) / $dely );
        $r = sqrt(($delx*$delx)+($dely*$dely));
        $latiso = log($b17/$r)/$b16;
        $sinphiit0 = tanh($latiso+$b10*atanh($b10*sin(1)));
        $sinphiit1 = tanh($latiso+$b10*atanh($b10*$sinphiit0));
        $sinphiit2 = tanh($latiso+$b10*atanh($b10*$sinphiit1));
        $sinphiit3 = tanh($latiso+$b10*atanh($b10*$sinphiit2));
        $sinphiit4 = tanh($latiso+$b10*atanh($b10*$sinphiit3));
        $sinphiit5 = tanh($latiso+$b10*atanh($b10*$sinphiit4));
        $sinphiit6 = tanh($latiso+$b10*atanh($b10*$sinphiit5));
        $longrad = $gamma/$b16+$b13/180*pi();
        $latrad = asin($sinphiit6);
        $long = ($longrad/pi()*180);
        $lat  = ($latrad/pi()*180);

        return array(
            'lambert93' => array(
                'x' => $x,
                'y' => $y
            ),
            'wgs84' => array(
                'lat' => $lat,
                'long' => $long
            )
        );
    }
}