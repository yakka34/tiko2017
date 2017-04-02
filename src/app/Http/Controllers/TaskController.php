<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Task;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    protected $page_name = 'Luo uusi tehtävä';
    //
    public function createTask(){
        if (Auth::user()->can('create-task')){
            return view('task',[
                'page_name' => $this->page_name,
            ]);
        }
        return back()->with('error','Ei oikeutta luoda tehtävää');
    }
    public function show($id){
        if (Auth::user()->can('create-task')){
            $task = Task::find($id);
            if ($task == null) return back()->with('error', 'Tehtävää ei ole olemassa');
            return view('task',['page_name' =>'Muokkaa tehtävää', 'task'=>$task]);
        }
        return back()->with('error','Ei oikeutta muokata tehtävää');
    }

    public function update(CreateTaskRequest $request, $id){
        $description = $this->filter_html($request->description);
        $task = Task::find($id);
        $task->description = $description;
        $task->type = strip_tags($request->type);
        $task->answer = strip_tags($request->answer);
        $task->save();
        return back()->with('status','Tehtävää muokattu');
    }

    public function save(CreateTaskRequest $request){
        $description = $this->filter_html($request->description);
        Task::create([
            'description' => $description,
            'type' => strip_tags($request->type),
            'user_id' => Auth::user()->id,
            'answer' => strip_tags($request->answer),
        ]);
        return back()->with('status','Tehtävä luotu');
    }

    private function filter_html($html) {
        return strip_tags($html,
            '<b><i><div><span><table><tr><td><th><tbody><img><p><h1><h2><h3><h4><h5><h6><br><ul><li><ol><strong><em><sup><sub><code><pre><blockquote>');
    }

}
