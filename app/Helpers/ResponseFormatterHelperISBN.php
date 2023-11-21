<?php

namespace App\Helpers;

class ResponseFormatterHelperISBN {

    public static function generateResponseOnlyBook(...$data)
    {
        $response = [];
        foreach($data as $datum)
        {
            foreach($datum as $marites)
            {
                if($marites){
                    if(!in_array(['isbn'=>$marites->isbn, 'book_id' => $marites->book_id, 'book_title' => $marites->book->title], $response))
                    {
                        array_push($response, ['isbn'=>$marites->isbn,'book_id' => $marites->book_id, 'book_title' => $marites->book->title]);
                    }
                }
            }
        }

        return $response;
    }

    public static function generateResponseOnlyYear(...$data)
    {
        $response = [];
        foreach($data as $datum)
        {
            foreach($datum as $marites)
            {
                if($marites){
                    if(!in_array($marites->year, $response))
                    {
                        array_push($response, $marites->year);
                    }
                }
            }
        }

        return $response;
    }

}
