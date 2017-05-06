<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $fillable = [
        'description', 'type', 'user_id', 'answer',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function sessiontasks() {
        return $this->hasMany(Sessiontask::class);
    }
}
