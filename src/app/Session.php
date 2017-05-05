<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Session extends Model {

    protected $fillable = ['user_id','tasklist_id','finished_at'];
    public $sandboxedDB = null;

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function tasklist() {
        return $this->belongsTo(Tasklist::class);
    }

    public function sessiontasks() {
        return $this->hasMany(Sessiontask::class);
    }

    public function finishedAt() {
        if ($this->finished_at == null) return null;
        return Carbon::parse($this->finished_at);
    }

    public function secondsTookToComplete() {
        if ($this->finished_at == null) return -1;
        $finished_at = Carbon::parse($this->finished_at);
        $created_at = Carbon::parse($this->created_at);
        return $finished_at->diffInSeconds($created_at);
    }

}
