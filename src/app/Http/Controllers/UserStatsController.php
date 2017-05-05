<?php

namespace App\Http\Controllers;

use App\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserStatsController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {

        // Hae suoritetut sessiot
        $completedSessions = Auth::user()->sessions()->whereNotNull('finished_at')->get();

        return view('home.userstats', [
            'page_name' => 'Tilastoja',
            'completed_sessions' => $completedSessions
        ]);

    }

}
