<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountUpdateRequest;
use Illuminate\Http\Request;

class AccountController extends Controller
{

    protected $page_name = 'Omat tiedot';

    public function __construct() {
        // Vaadi käyttäjän todentaminen
        $this->middleware('auth');
    }

    public function index() {
        return view('account', ['page_name' => $this->page_name]);
    }

    public function save(AccountUpdateRequest $request) {
        $request->persist();
        return back()->with('status', 'Tiedot päivitetty');
    }

}
