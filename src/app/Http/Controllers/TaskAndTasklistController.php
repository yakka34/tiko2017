<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskAndTasklistController extends Controller
{

    public $page_name = 'Tehtävät ja tehtävälistat';

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        if (Auth::user()->cant('create-task')) {
            return back()->with('error', 'Ei oikeutta!');
        }
        return view('missioncontrol.index', [
            'page_name' => $this->page_name,
            'tasklists' => Auth::user()->tasklists,
            'tasks' => Auth::user()->tasks
        ]);
    }

    public function tasks() {
        return Auth::user()->tasks;
    }

    public function tasklists() {
        return Auth::user()->tasklists;
    }

}
