<?php

namespace App\Http\Controllers;

use App\Session;
use App\Task;
use App\Tasklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            return view('tasklist', ['page_name' => 'Session tehtävälista','tasklist' => $tasklist]);
        }
        return back()->with('error','Sessiota ei ole olemassa tai sinulla ei ole oikeuksia nähdä sitä');
    }

    public function show($session, $tasklist, $task){
        $session = Session::find($session);
        $tasklist = Tasklist::find($tasklist);
        $task = Task::find($task);
        //TODO: Muokkaa näkymä näyttämään edellinen ja seuraava.
        return view('showtask',[
            'page_name' => 'Sessio tehtävä',
            'previous' => $this->previous($tasklist,$task,$session),
            'next' => $this->next($tasklist,$task,$session),
            'task' => $task,
            'tasklist' => $tasklist->id,
        ]);

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
