<?php
namespace App\Helpers;

use Carbon\Carbon;
use DateTime;


class FileHelper {

public static function fileExists($url)
{
    
}

public static function createDirBySite($site)
{
    $date = Carbon::now();
    $month = $date->format('m');
    $year = $date->format('Y');
    $day = $date->format('d');
    $reports = storage_path('app\reports');

    $siteName = UrlHelper::getOnlySiteName($site);

    $fullPath = "{$reports}/{$year}/{$month}/{$day}/{$siteName}";

    if(!file_exists($fullPath))
        mkdir($fullPath, 0755, true);
    
    return $fullPath;
}

}