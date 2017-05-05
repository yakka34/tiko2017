<?php

namespace App\Http\Controllers;

use App\Role;
use App\Session;
use App\User;
use App\Http\Requests\RoleUpdateRequest;
use Illuminate\Http\Request;

class adminController extends Controller
{
    protected $page_name = 'Hallintapaneeli';

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('\App\Http\Middleware\CheckRole:admin')->only(['addRole', 'removeRole']);    // addRole- ja removeRole- metodit vaativat admin-oikeudet
        //$this->middleware('App\Http\Middleware\CheckRole:admin');
    }

    public function index(){
        $users = User::all();

        if (!\Auth::user()->hasRole('admin') && !\Auth::user()->hasRole('teacher')) {
            return back()->with('error', 'Ei oikeutta!');
        }

        // Opettajalla on oikeus nähdä niiden opiskelijoiden tiedot, jotka ovat tehneet hänen tehtävälistojaan
        // Muuten opettaja ei saa nähdä opiskelijoiden tietoja
        if (\Auth::user()->hasRole('teacher')) {
            // Hae opiskelijat, jotka ovat tehneet opettajan tehtävälistoja
            $users = User::whereIn('id', Session::whereIn('tasklist_id', \Auth::user()->tasklists()->pluck('id'))->pluck('user_id'))->get();
        }

        return view('admin', [
            'users' => $users,
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
