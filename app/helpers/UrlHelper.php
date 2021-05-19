<?php
namespace App\Helpers;

class UrlHelper {

public static function getOnlySiteName($url)
{
    $siteName = (preg_replace('#^www\.(.+\.)#i', '$1', parse_url($url, PHP_URL_HOST)));
    $siteName = str_replace(['.com', '.br'], '', $siteName);


    return $siteName;
}

}