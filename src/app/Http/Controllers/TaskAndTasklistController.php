<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTasklistRequest;
use App\Tasklist;
use App\Utils\HTMLHelper;
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

    public function show($id){
        $tasklist = Tasklist::find($id);
        if($tasklist != null){
            return view('tasklist',['page_name' => 'Tehtävälista', 'tasklist'=>$tasklist]);
        }
        return back('error','Tehtävälistaa ei ole olemassa');
    }

    public function saveTasklist(CreateTasklistRequest $request) {
        if (Auth::user()->cant('create-task')) {
            return ['status' => 'fail', 'message' => 'Ei oikeutta!'];
        }
        $tasklist = Tasklist::create([
            'description' => HTMLHelper::filter_bad_tags($request->description),
            'user_id' => Auth::user()->id
        ]);

        // Liitä tehtävät tehtävälistaan
        $tasklist->tasks()->attach($request->tasks);

        return ["status" => "ok", "tasklist" => $tasklist];
    }

    public function tasks() {
        return Auth::user()->tasks;
    }

    public function tasklists() {
        return Auth::user()->tasklists;
    }

}
