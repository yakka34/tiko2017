<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Etsii tunnistautuneen käyttäjän ja lisää hänelle admin oikeudet
        $user = User::find(Auth::user()->id);
        $user->roles()->attach(1);
        //Palautetaan käyttäjän tiedot home näkymään
        return view('home',compact('user'));
    }
}
