<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BackupRequest extends Model
{
    protected $fillable = [
        'request_number',
        'user_id',
        'parking_location_id',
        'backup_item_id',
        'quantity',
        'reason',
        'priority',
        'status',
        'verified_by',
        'verified_at',
        'verification_note',
        'rejection_reason',
        'processed_by',
        'processed_at',
        'completed_at',
        'handover_photo',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parkingLocation()
    {
        return $this->belongsTo(ParkingLocation::class);
    }

    public function backupItem()
    {
        return $this->belongsTo(BackupItem::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}