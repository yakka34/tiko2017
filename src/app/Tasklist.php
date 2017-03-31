<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tasklist extends Model
{
    //
    public function tasks(){
        $this->belongsToMany(Task::class);
    }
}
