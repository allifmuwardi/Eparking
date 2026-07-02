<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportFollowUp extends Model
{
    protected $fillable = [
        'issue_report_id',
        'technician_id',
        'previous_status',
        'new_status',
        'follow_up_note',
        'documentation_photo',
        'need_backup_item',
        'backup_item_id',
        'backup_item_quantity',
        'backup_item_note',
    ];

    protected $casts = [
        'need_backup_item' => 'boolean',
    ];

    public function issueReport()
    {
        return $this->belongsTo(IssueReport::class);
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function backupItem()
    {
        return $this->belongsTo(BackupItem::class);
    }
}