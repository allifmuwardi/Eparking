<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssueReport extends Model
{
    protected $fillable = [
        'report_number',
        'user_id',
        'parking_location_id',
        'title',
        'category',
        'priority',
        'description',
        'photo',
        'status',
        'assigned_technician_id',
        'verified_by',
        'verified_at',
        'verification_note',
        'rejection_reason',
        'closed_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parkingLocation()
    {
        return $this->belongsTo(ParkingLocation::class);
    }

    public function assignedTechnician()
    {
        return $this->belongsTo(User::class, 'assigned_technician_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function followUps()
    {
        return $this->hasMany(ReportFollowUp::class);
    }

    public function histories()
    {
        return $this->hasMany(ReportHistory::class);
    }
}