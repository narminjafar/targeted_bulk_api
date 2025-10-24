<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid', 'name', 'subject', 'template_key', 'content',
        'from_email', 'segment_id', 'status',
        'total_recipients', 'sent_count', 'error_count',
        'scheduled_at',
    ];

    

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($campaign) {
            $campaign->uuid = (string) Str::uuid();
        });
    }

    public function segment()
    {
        return $this->belongsTo(\App\Models\Segment::class);
    }

}
