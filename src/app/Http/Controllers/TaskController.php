<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Task;
use Illuminate\Http\Request;

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
            $request->description,
            $request->type,
            $request->answer,
        ]);
        return back()-with('status','Tehtävä luotu');
    }
}
