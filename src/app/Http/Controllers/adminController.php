<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class adminController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('App\Http\Middleware\CheckRole:admin');
    }

    public function index(){
        $users = User::all();
        return view('admin',compact(['users']));
    }

}
