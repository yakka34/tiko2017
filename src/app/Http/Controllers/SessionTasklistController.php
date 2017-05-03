<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskAnswerRequest;
use App\Session;
use App\Sessiontask;
use App\Task;
use App\Tasklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SessionTasklistController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($session){
        $session = Session::find($session);
        $user = Auth::user();
        if($session != null && $session->user_id == $user->id && $session->finished_at == null){
            $tasklist = Tasklist::find($session->tasklist_id);
            return view('session.tasklist', [
                'page_name' => 'Session tehtävälista',
                'tasklist' => $tasklist,
                'session' => $session->id,
            ]);
        }
        return back()->with('error','Sessiota ei ole olemassa tai sinulla ei ole oikeuksia nähdä sitä');
    }

    public function show($session, $tasklist, $task){
        $session = Session::find($session);
        $tasklist = Tasklist::find($tasklist);
        $task = Task::find($task);
        $sessiontask = Sessiontask::firstOrCreate(['session_id' => $session->id, 'task_id' => $task->id]);
        return view('session.task',[
            'page_name' => 'Sessio tehtävä',
            'previous' => $this->previous($tasklist,$task,$session),
            'next' => $this->next($tasklist,$task,$session),
            'task' => $task,
            'session' => $session->id,
            //'finished' => $sessiontask->finished_at,
        ]);

    }

    public function answer(TaskAnswerRequest $request,$session,$tasklist,$task){
        $task = Task::find($task);
        try{
            $query = DB::select($request->input('query'));
            $answer = DB::select($task->answer);
            if($query == $answer){
                return back()->with('status','Oikein meni!');
            }
            return back()->with('error' ,'Väärä vastaus');
        }
        catch (\Illuminate\Database\QueryException $e){
            return back()->with('error','SQL-kysely virheellinen');
        }
    }

    private function previous($tasklist,$task,$session){
        if ($tasklist->tasks->first()->is($task)){
            return route('session.show.tasklist',$session->id,$tasklist->id);
        }
        $previous_task = $tasklist->tasks->where('id','<',$task->id)->max();
        return route('session.show.task',[$session->id,$tasklist->id,$previous_task->id]);
    }

    private function next($tasklist,$task,$session){
        if ($tasklist->tasks->last()->is($task)){
            return route('session.show.tasklist',$session->id,$tasklist->id);
        }
        $next_task = $tasklist->tasks->where('id','>',$task->id)->min();
        return route('session.show.task',[$session->id,$tasklist->id,$next_task->id]);
    }
}
