<?php

namespace App\Factories\AuditToolFactory;

use App\Exceptions\AuditFailedException;
use App\Helpers\Lighthouse;

class AuditToolFactory {
    public function __construct()
    {
        
    }

    public static function create($toolName) 
    {
        switch($toolName)
        {
            case "Lighthouse":
                return new Lighthouse();
            case "WAVE":
                throw new AuditFailedException($toolName);
                break;
            default:
                throw new AuditFailedException($toolName);
        }
    }
}