<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    protected $page_name = 'Tervetuloa';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home', ['page_name' => $this->page_name]);
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
