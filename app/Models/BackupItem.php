<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackupItem extends Model
{
    protected $fillable = [
        'item_code',
        'item_name',
        'category',
        'stock',
        'unit',
        'storage_location',
        'description',
        'status',
    ];

    public function backupRequests()
    {
        return $this->hasMany(BackupRequest::class);
    }

    public function reportFollowUps()
    {
        return $this->hasMany(ReportFollowUp::class);
    }
}