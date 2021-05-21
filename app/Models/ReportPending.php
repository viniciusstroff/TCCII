<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportPending extends Model
{
    use HasFactory;

    protected $table = 'reports_pending';
    protected $id = 'id';
    protected $foreinKey = 'report_id';

    protected $fillable = [
        'id',
        'report_id',
        'is_finished',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s'
    ];


    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
