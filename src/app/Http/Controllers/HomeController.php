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
        $user = Auth::user();
        return view('home',compact('user'));
    }

    public function account(){
        //Hakee tunnistautuneen käyttäjän
        $user = Auth::user();
        //Käyttäjän roolit
        $roles = $user->roles;
        //Molemmat tiedot välitetään näkymälle
        return view('account', compact('user','roles'));
    }

}
