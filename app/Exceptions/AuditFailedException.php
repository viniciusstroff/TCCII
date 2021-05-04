<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Log;

class AuditFailedException extends \Exception
{
    protected $output;

    public function __construct($url, $output = null)
    {
        Log::error("Error to audit of {$url}");
        parent::__construct("Audit of '{$url}' failed");

        $this->output = $output;
    }

    public function getOutput()
    {
        return $this->output;
    }
}
