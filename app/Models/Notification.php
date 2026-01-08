<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'target_user_id',
        'title',
        'message',
        'is_global',
        'notification_type',
        'image_url',
        'is_read',
    ];

    protected $casts = [
        'is_global' => 'boolean',
        'is_read' => 'boolean',
    ];

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }
}
