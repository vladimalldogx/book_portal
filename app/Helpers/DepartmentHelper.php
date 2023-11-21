<?php

namespace App\Helpers;

use Faker\Core\Number;

class DepartmentHelper {

    private static $dept = [
      
        
        1 => 'SALES',
        2 => 'Operations Excellence',
        3 => 'Publishing Production',
        4 => 'Lead Management',
        5 =>'Author Relation Officer'
    
    ];

    public static function getNumerictype($number = 0)
    {
        return array_search($number,self::$dept);
    }

    public static function getStringtype($string = '')
    {
        return self::$dept[$string];
    }

    public static function getDepartment()
    {
        return self::$dept;
    }

}
