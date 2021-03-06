<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'studentId', 'major'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'roles', 'id', 'updated_at'
    ];

    public function hasRole($role){
        foreach ($this->roles()->get() as $role_) {
            if ($role_->name == $role) {
                return true;
            }
        }
        return false;
    }

    public function roles(){
        return $this->belongsToMany(Role::class);
    }

    public function tasks() {
        return $this->hasMany(Task::class);
    }

    public function tasklists() {
        return $this->hasMany(Tasklist::class);
    }

    public function sessions() {
        return $this->hasMany(Session::class);
    }

    public function sessiontasks() {
        return $this->hasMany(Sessiontask::class);
    }
}
