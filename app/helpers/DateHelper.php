<?php
namespace App\Helpers;

use Carbon\Carbon;

class DateHelper {

    public static function convertDateToIsoFormat(String $dateBrazilianFormat): String
    {
        $date = Carbon::parse($dateBrazilianFormat);
        $date = $date->format("Y-m-d H:i:s");

        return $date;
    }

    public static function convertDateToBrazilianFormat(String $dateIsoFormat): String
    {
        
        $date = Carbon::parse($dateIsoFormat);
        
        $date = $date->format("d/m/Y H:i:s");
        return $date;
    }
}