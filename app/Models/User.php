<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'nip',
        'name',
        'full_name',
        'email',
        'password',
        'role',
        'phone',
        'status',
        'parking_location_id',
        'profile_photo',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function parkingLocation()
    {
        return $this->belongsTo(ParkingLocation::class, 'parking_location_id');
    }

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

    public function assignedReports()
    {
        return $this->hasMany(IssueReport::class, 'assigned_technician_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Role Helpers
    |--------------------------------------------------------------------------
    */

    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }

    public function isTeknisi(): bool
    {
        return $this->role === 'teknisi';
    }

    public function isManajer(): bool
    {
        return $this->role === 'manajer';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAdminOperasional(): bool
    {
        return $this->role === 'admin';
    }

    public function requiresOperationalLocation(): bool
    {
        return in_array($this->role, ['petugas', 'teknisi'], true);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessor Helpers
    |--------------------------------------------------------------------------
    */

    public function getDisplayNameAttribute(): string
    {
        return $this->full_name ?: $this->name ?: $this->username ?: 'User';
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'petugas' => 'Petugas Parkir',
            'teknisi' => 'Teknisi Vendor',
            'manajer' => 'Manajer Operasional',
            'admin' => 'Admin Operasional',
            default => 'Pengguna',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'Aktif' => 'bg-success',
            'Tidak Aktif' => 'bg-secondary',
            default => 'bg-secondary',
        };
    }

    public function getOperationalLocationLabelAttribute(): string
    {
        if (!$this->parkingLocation) {
            return 'Belum ditentukan';
        }

        $locationName = $this->parkingLocation->location_name ?? '-';
        $locationCode = $this->parkingLocation->location_code ?? null;

        if ($locationCode) {
            return $locationName . ' (' . $locationCode . ')';
        }

        return $locationName;
    }

    public function getProfilePhotoUrlAttribute(): ?string
    {
        if (empty($this->profile_photo)) {
            return null;
        }

        if (Str::startsWith($this->profile_photo, ['http://', 'https://'])) {
            return $this->profile_photo;
        }

        if (Str::startsWith($this->profile_photo, 'storage/')) {
            return asset($this->profile_photo);
        }

        return asset('storage/' . $this->profile_photo);
    }
}
