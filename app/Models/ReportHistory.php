<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportHistory extends Model
{
    protected $fillable = [
        'issue_report_id',
        'user_id',
        'action',
        'previous_status',
        'new_status',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function issueReport()
    {
        return $this->belongsTo(IssueReport::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}