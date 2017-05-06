<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tasklist extends Model
{

    protected $fillable = [
        'description',
        'user_id'
    ];

    public function tasks() {
        return $this->belongsToMany(Task::class);
    }

    public function sessions() {
        return $this->hasMany(Session::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
