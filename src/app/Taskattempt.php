<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Taskattempt extends Model
{
    //
    protected $fillable = ['sessiontask_id','finished_at','answer'];

    public function sessiontask(){
        return $this->belongsTo(Sessiontask::class);
    }
}
