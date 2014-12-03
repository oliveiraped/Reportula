<?php

class AppHelper {

    /**
     * Formats a numbers as bytes, based on size, and adds the appropriate suffix
     *
     * @access  public
     * @param mixed // will be cast as int
     * @return string
     */

    public static function byte_format($num, $precision = 1)
    {

        if ($num >= 1000000000000) {
            $num = round($num / 1099511627776, $precision);
            $unit = 'Tb';
        } elseif ($num >= 1000000000) {
            $num = round($num / 1073741824, $precision);
            $unit = 'Gb';
        } elseif ($num >= 1000000) {
            $num = round($num / 1048576, $precision);
            $unit ='Mb';
        } elseif ($num >= 1000) {
            $num = round($num / 1024, $precision);
            $unit = 'Kb';
        } else {
            $unit = 'b';

            return number_format($num).' '.$unit;
        }

        return number_format($num, $precision).' '.$unit;
    }
}
