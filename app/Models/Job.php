<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = "jobs";

    protected $fillable = [
        'id',
        'queue',
        'payload',
        'reserved_at',
        'available_at',
        'attempts',
        'created_at'

    ];
}
