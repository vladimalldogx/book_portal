<?php

namespace App\Helpers;

class NumberFormatterHelper
{
    public static function numtowords($num)
    {
        $decones = array(
                    '01' => "one",
                    '02' => "two",
                    '03' => "three",
                    '04' => "four",
                    '05' => "five",
                    '06' => "six",
                    '07' => "seven",
                    '08' => "eight",
                    '09' => "nine",
                    10 => "ten",
                    11 => "eleven",
                    12 => "twelve",
                    13 => "thirteen",
                    14 => "fourteen",
                    15 => "fifteen",
                    16 => "sixteen",
                    17 => "seventeen",
                    18 => "eighteen",
                    19 => "nineteen"
                    );
        $ones = array(
                    0 => " ",
                    1 => "one",
                    2 => "two",
                    3 => "three",
                    4 => "four",
                    5 => "five",
                    6 => "six",
                    7 => "seven",
                    8 => "eight",
                    9 => "nine",
                    10 => "ten",
                    11 => "eleven",
                    12 => "twelve",
                    13 => "thirteen",
                    14 => "fourteen",
                    15 => "fifteen",
                    16 => "sixteen",
                    17 => "seventeen",
                    18 => "eighteen",
                    19 => "nineteen"
                    );
        $tens = array(
                    0 => "",
                    2 => "twenty",
                    3 => "thirty",
                    4 => "forty",
                    5 => "fifty",
                    6 => "sixty",
                    7 => "seventy",
                    8 => "eighty",
                    9 => "ninety"
                    );
        $hundreds = array(
                    "hundred",
                    "thousand",
                    "million",
                    "billion",
                    "trillion",
                    "quadrillion"
                    ); //limit t quadrillion
        $num = number_format($num,2,".",",");
        $num_arr = explode(".",$num);
        $wholenum = $num_arr[0];
        $decnum = $num_arr[1];
        $whole_arr = array_reverse(explode(",",$wholenum));
        krsort($whole_arr);
        $rettxt = "";
        foreach($whole_arr as $key => $i){
            if($i < 20){
                $rettxt .= $ones[$i];
            }
            elseif($i < 100){
                $rettxt .= $tens[substr($i,0,1)];
                $rettxt .= " ".$ones[substr($i,1,1)];
            }
            else{
                $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0];
                $rettxt .= " ".$tens[substr($i,1,1)];
                $rettxt .= " ".$ones[substr($i,2,1)];
            }
            if($key > 0){
                $rettxt .= " ".$hundreds[$key]." ";
            }

        }
        $rettxt = $rettxt." dollars";

        if($decnum > 0){
            $rettxt .= " and ";
            if($decnum < 20){
                $rettxt .= $decones[$decnum];
            }
            elseif($decnum < 100){
                $rettxt .= $tens[substr($decnum,0,1)];
                $rettxt .= " ".$ones[substr($decnum,1,1)];
            }
            $rettxt = $rettxt." cents only";
        }
        return $rettxt;
    }
}
