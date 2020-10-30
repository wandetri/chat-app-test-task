<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public function user_message(){
        return $this->hasOne(UserMessage::class);
    }

    public function users(){
        return $this->belongsToMany(User::class, 'user_messages','message_id','sender_id')
        ->withTimestamps();
    }
}
