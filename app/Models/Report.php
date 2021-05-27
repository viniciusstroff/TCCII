<?php

namespace App\Models;

use App\Helpers\DateHelper;
use App\Helpers\UrlHelper;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    const TABLE_NAME = "reports";
    const PENDING_STATUS = 0;
    const FINISHED_STATUS = 1;

    protected $table = self::TABLE_NAME;
    protected $id = 'id';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'id',
        'tool_name',
        'site'
    ];

    // protected $casts = [
    //     'created_at' => 'datetime:d/m/Y',
    //     'updated_at' => 'datetime:d/m/Y'
    // ];


    public function reportDocuments()
    {
        return $this->hasMany(ReportDocument::class);
    }

    public function getCreatedAtAttribute($createdAt)
    {
        return DateHelper::convertDateToBrazilianFormat($createdAt);
    }

    public function getUpdatedAtAttribute($updatedAt)
    {
        return DateHelper::convertDateToBrazilianFormat($updatedAt);
    }


    public function setCreatedAtAttribute($createdAt)
    {
        $this->attributes['created_at'] = DateHelper::convertDateToIsoFormat($createdAt);
    }

    public function setUpdatedAtAttribute($updatedAt)
    {
        $this->attributes['updated_at'] = DateHelper::convertDateToIsoFormat($updatedAt);
    }

   

}
