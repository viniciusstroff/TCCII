<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';
    protected $id = 'id';

    protected $fillable = [
        'id',
        'tool_name',
        'site',
        'file_name',
        'file_format',
        'file_fake_name',
        'created_at',
        'updated_at'
    ];


    public function reportPending()
    {
        return $this->hasOne(ReportPending::class);
    }
}
