<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyTrafficReport extends Model
{
    protected $fillable = [
        'user_id',
        'parking_location_id',
        'report_date',
        'shift',
        'total_vehicle_in',
        'total_vehicle_out',
        'car_count',
        'motorcycle_count',
        'other_vehicle_count',
        'total_transaction',
        'total_revenue',
        'notes',
        'photo',
    ];

    protected $casts = [
        'report_date' => 'date',
        'total_revenue' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parkingLocation()
    {
        return $this->belongsTo(ParkingLocation::class);
    }
}