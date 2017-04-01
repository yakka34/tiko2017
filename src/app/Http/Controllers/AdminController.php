<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;

class adminController extends Controller
{
    protected $page_name = 'Hallintapaneeli';

    public function __construct()
    {
        $this->middleware('App\Http\Middleware\CheckRole:admin');
    }

    public function index(){
        $users = User::all();
        return view('admin',
            ['users' => $users,
            'page_name' => $this->page_name,
            ]);
    }

    public function addRole(RoleUpdateRequest $request, int $id){
        $user = User::find($id);
        $user->roles()->attach($request->role);
        return back()->with('status','Rooli: ' . Role::find($request->role)->name . ' lisätty käyttäjälle');

    }

    public function removeRole(RoleUpdateRequest $request, int $id){
        $user = User::find($id);
        $user->roles()->detach($request->role);
        return back()->with('status', 'Rooli: ' . Role::find($request->role)->name . ' poistettu käyttäjältä');

    }
}
