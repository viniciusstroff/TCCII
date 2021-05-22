<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    const TABLE_NAME = "reports";

    protected $table = self::TABLE_NAME;
    protected $id = 'id';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

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

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s'
    ];


    public function reportPending()
    {
        return $this->hasOne(ReportPending::class);
    }
}
