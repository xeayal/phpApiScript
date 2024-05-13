<?php
namespace helpers;

class Date {
    public static function beautyDate($date, $lang)
    {
        $d = date("d", strtotime($date));
        $m = date("m", strtotime($date));
        $y = date("Y", strtotime($date));
        $h = date("h", strtotime($date));
        $i = date("i", strtotime($date));
        $s = date("s", strtotime($date));

        $m = $m - 1;
        $months['az'] = ['Yanvar', 'Fevral', 'Mart', 'Aprel', 'May', 'İyun', 'İyul', 'Avqust', 'Sentyabr', 'Oktyabr', 'Noyabr', 'Dekabr'];
        $months['tr'] = ["Ocak", "Şubat", "Mart", "Nisan", "Mayıs", "Haziran", "Temmuz", "Ağustos", "Eylül", "Ekim", "Kasım", "Aralık"];
        $months['en'] = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        return $d.' '.$months[$lang][$m].' '.$y.' '.$h.':'.$i.':'.$s;
    }

    public static function yesterday()
    {
        return date('Y-m-d H:i:s', strtotime('-1 day'));
    }

    public static function tomorrow()
    {
        return date('Y-m-d H:i:s', strtotime('+1 day'));
    }

    public static function fullDate()
    {
        return date('Y-m-d H:i:s');
    }
}