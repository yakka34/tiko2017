<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskAnswerRequest;
use App\Session;
use App\Sessiontask;
use App\Task;
use App\Taskattempt;
use App\Tasklist;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
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
        if($sessiontask->correct || count(Taskattempt::where('sessiontask_id', $sessiontask->id)->get()) >= 3){
            //TODO Estä tehtävän tekeminen käyttöliittymässä.
            //TODO Mitä tehdä kun tehtävä on suoritettu tai yritykset käytetty?
            return view('session.task',[
                'page_name' => 'Sessio tehtävä',
                'previous' => $this->previous($tasklist,$task,$session),
                'next' => $this->next($tasklist,$task,$session),
                'task' => $task,
                'session' => $session->id,
            ])->with('status','Tehtävä suoritettu');
        }
        Taskattempt::firstOrCreate(['sessiontask_id' => $sessiontask->id, 'finished_at' => null]);
        return view('session.task',[
            'page_name' => 'Sessio tehtävä',
            'previous' => $this->previous($tasklist,$task,$session),
            'next' => $this->next($tasklist,$task,$session),
            'task' => $task,
            'session' => $session->id,
        ]);

    }

    public function answer(TaskAnswerRequest $request,$session,$tasklist,$task){
        $task = Task::find($task);
        $sessiontask = Sessiontask::find(['session_id' => $session, 'task_id' => $task->id])->last();
        $taskattempt = Taskattempt::where(['sessiontask_id' => $sessiontask->id])->get();
        if(count($taskattempt) >= 3 && $taskattempt->last()->finished_at != null){
            $sessiontask->finished_at = Carbon::now();
            $sessiontask->save();
            return back()->with('error','Kolme yritystä käytetty');
        }
        try{
            $query = DB::select($request->input('query'));
            $answer = DB::select($task->answer);
            $taskattempt->last()->finished_at = Carbon::now();
            $taskattempt->last()->answer = $request->input('query');
            $taskattempt->last()->save();
            if($query == $answer){
                $sessiontask->correct = true;
                $sessiontask->finished_at = Carbon::now();
                $sessiontask->save();
                return back()->with('status','Oikein meni!');
            }
            return back()->with('error' ,'Väärä vastaus');
        }
        catch (QueryException $e){
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
