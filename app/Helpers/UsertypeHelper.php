<?php

namespace App\Helpers;

use Faker\Core\Number;

class UsertypeHelper {

    private static $usertype = [
      
        0 => 'unassigned',
        1 => 'superadmin',
        2 => 'admin',
        3 => 'manager',
        4 => 'regularuser',
    
    ];

    public static function getNumerictype($number = 0)
    {
        return array_search($number,self::$usertype);
    }

    public static function getStringtype($string = '')
    {
        return self::$usertype[$string];
    }

    public static function gettype()
    {
        return self::$usertype;
    }

}
