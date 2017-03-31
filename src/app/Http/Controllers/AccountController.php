<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountUpdateRequest;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AccountController extends Controller
{

    protected $page_name = 'Omat tiedot';

    public function __construct() {
        // Vaadi käyttäjän todentaminen
        $this->middleware('auth');
        $this->middleware('\App\Http\Middleware\CheckRole:admin')->only('show');    // show-metodi vaatii admin-roolin
    }

    public function index() {
        return view('account', [
            'page_name' => $this->page_name,
            'user' => Auth::user()
        ]);
    }

    public function show($id) {
        $user = User::find($id);
        return view('account', [
            'page_name' => $this->page_name,
            'user' => $user
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
