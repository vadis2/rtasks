<?php
/**
 * Project: rtasks.com
 * File: Datetime_helper.php.
 * Author: Volodymyr Vadiasov <vadiasov.volodymyr@gmail.com>
 * Date: 28.10.16
 * Time: 19:39
 */
/**
 * @param string $dateString
 * @param integer $interval
 * @return string
 */

function addDays($dateString, $interval)
{
    $date = new DateTime($dateString);
    $date->add(new DateInterval('P' . $interval . 'D'));
    
    return $date->format('Y-m-d H:i:s');
}

function convert($dateString)
{
    $array_1   = explode(' ', $dateString);
    $array_1_1 = explode('.', $array_1[0]);
    
    return $array_1_1[2] . '-' . $array_1_1[1] . '-' . $array_1_1[0] . ' ' . $array_1[1] . ':00';
}

function addMonths($date, $months)
{
    $years = floor(abs($months / 12));
    $leap = 29 <= $date->format('d');
    $m = 12 * (0 <= $months?1:-1);
    for ($a = 1;$a < $years;++$a) {
        $date = addMonths($date, $m);
    }
    $months -= ($a - 1) * $m;
    
    $init = clone $date;
    if (0 != $months) {
        $modifier = $months . ' months';
        
        $date->modify($modifier);
        if ($date->format('m') % 12 != (12 + $months + $init->format('m')) % 12) {
            $day = $date->format('d');
            $init->modify("-{$day} days");
        }
        $init->modify($modifier);
    }
    
    $y = $init->format('Y');
    if ($leap && ($y % 4) == 0 && ($y % 100) != 0 && 28 == $init->format('d')) {
        $init->modify('+1 day');
    }
    return $init;
}