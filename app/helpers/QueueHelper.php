<?php
namespace App\Helpers;

class QueueHelper {

public static function getQueueName(String $queue, String $value) :String
{
    $queueName = "{$queue}_{$value}";

    return $queueName;
}


}