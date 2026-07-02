<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingLocation extends Model
{
    protected $fillable = [
        'location_code',
        'location_name',
        'address',
        'area',
        'pic_name',
        'pic_phone',
        'status',
    ];

    public function issueReports()
    {
        return $this->hasMany(IssueReport::class);
    }

    public function dailyTrafficReports()
    {
        return $this->hasMany(DailyTrafficReport::class);
    }

    public function backupRequests()
    {
        return $this->hasMany(BackupRequest::class);
    }
}