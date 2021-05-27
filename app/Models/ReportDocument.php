<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportDocument extends Model
{
    use HasFactory;

    const TABLE_NAME = "report_documents";

    protected $table = self::TABLE_NAME;
    protected $id = 'id';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'id',
        'report_id',
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


    public function report()
    {
        return $this->hasOne(Report::class);
    }

    public function getFileFakeNameAttriute()
    {
        return $this->getFileFakeName();
    }

    public function setFileFakeNameAttribute($value) 
    {
        $fileFakeName = $this->getFileFakeName($value);
        $this->attributes['file_fake_name'] = $fileFakeName;
    }

    public function getFileFakeName($value = null)
    {   
        $fileFakeName = $value ? $value : $this->file_fake_name;
            
        return "{$fileFakeName}.{$this->file_format}";
    }
}
