<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public $timestamps = false;
    /*
     * Suhde julistettu Role-luokassa
    public function roles() {
        return $this->belongsToMany(Role::class);
    }
    */
}
