<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountUpdateRequest;
use App\Http\Requests\RoleUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use App\Role;

class AccountController extends Controller
{

    protected $page_name = 'Omat tiedot';

    public function __construct() {
        // Vaadi käyttäjän todentaminen
        $this->middleware('auth');
        $this->middleware('\App\Http\Middleware\CheckRole:admin')->only(['show']);    // show-metodi vaatii admin-roolin
        //$this->middleware('\App\Http\Middleware\CheckRole:admin')->only('addRole');
    }

    public function index() {
        return view('account', [
            'page_name' => $this->page_name,
            'user' => Auth::user()
        ]);
    }

    public function show($id) {
        $user = User::find($id);
        $roles = Role::all()->diff($user->roles);
        return view('account', [
            'page_name' => $this->page_name,
            'user' => $user,
            'roles' => $roles,
        ]);
    }


    public function save(AccountUpdateRequest $request, int $id) {
        $user = Auth::user();
        if ($user->hasRole('admin') || $user->id === $id) {
            $request->persist($id);
            return back()->with('status', 'Tiedot päivitetty');
        } else {
            return back()->with('error', 'Ei oikeutta!');
        }
    }

}
