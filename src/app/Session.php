<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $fillable = ['user_id','tasklist_id','finished_at'];
    public $sandboxedDB = null;
}
