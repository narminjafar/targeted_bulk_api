<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserUnsubscribed extends Model
{
    use HasFactory;

    protected $table = 'user_unsubscribed';
    protected $fillable = ['user_id', 'campaign_id', 'signature'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
