<?php
namespace App\Helpers;

class QueueHelper {

public static function getQueueName($queue, $value)
{
    $queueName = "{$queue}_{$value}";

    return $queueName;
}

}