<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportScore extends Model
{
    use HasFactory;

    const TABLE_NAME = "scores";

    protected $table = self::TABLE_NAME;
    protected $id = 'id';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'id',
        'accessibility',
        'performance',
        'seo',
        'best-pratices',
        'report_id'
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s'
    ];


    public function report()
    {
        return $this->hasOne(Report::class);
    }
}
