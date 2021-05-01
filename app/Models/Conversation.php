<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'recipient_id',
        'message_count',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, );
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, );
    }
}
