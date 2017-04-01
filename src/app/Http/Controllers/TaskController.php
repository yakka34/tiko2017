<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    protected $page_name = 'Luo uusi tehtävä';
    //
    public function createTask(){
        return view('task',[
            'page_name' => $this->page_name,
        ]);
    }

    public function save(CreateTaskRequest $request){
        Task::create([
            'description' => $request->description,
            'type' => $request->type,
            'author' => Auth::user()->id,
            'answer' => $request->answer,
        ]);
        return back()->with('status','Tehtävä luotu');
    }
}
