<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskAnswerRequest;
use App\Session;
use App\Sessiontask;
use App\Task;
use App\Taskattempt;
use App\Tasklist;
use App\Utils\SandboxedDatabase;
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
            // Tehtävä suoritettu hyväksytysti tai kaikki yritykset käytetty
            // Merkataan tehtävä suoritetuksi, jos se ei ole jo
            if ($sessiontask->finished_at == null) {
                $sessiontask->finished_at = Carbon::now();
                $sessiontask->save();
            }
            // Palataan tehtävälistaan
            return redirect()->route('session.show.tasklist', ['session_id' => $session])->with('status', 'Tehtävä suoritettu');
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
        $sessiontask = Sessiontask::where(['session_id' => $session, 'task_id' => $task->id])->get()->last();
        $taskattempt = Taskattempt::where(['sessiontask_id' => $sessiontask->id])->get();
        if(count($taskattempt) >= 3 && $taskattempt->last()->finished_at != null){
            $sessiontask->finished_at = Carbon::now();
            $sessiontask->save();
            return redirect()->route('session.show.tasklist', ['session_id' => $session])->with('error', 'Kolme yritystä käytetty!');
        }
        $sess = Session::find($session);
        $sess->sandboxedDB = new SandboxedDatabase($sess);

        try {
            // Ota tietokannan tämänhetkinen tilanne talteen
            $sess->sandboxedDB->backupTables();
            $query = $sess->sandboxedDB->runSelect($request->input('query'), false); // false parametrina jos ajetaan käyttäjän kysely
            $answer = $sess->sandboxedDB->runSelect($task->answer, true);    // true parametrina jos tarkistetaan
            $taskattempt->last()->finished_at = Carbon::now();
            $taskattempt->last()->answer = $request->input('query');
            $taskattempt->last()->save();
            if($query == $answer){
                $sessiontask->correct = true;
                $sessiontask->finished_at = Carbon::now();
                $sessiontask->save();
                return back()->with('status','Oikein meni!');
            }

            // Palauta edellinen tietokannan tilanne epäonnistuneen kyselyn jälkeen
            $sess->sandboxedDB->restoreTables();

            $sessiontask->correct = false;
            $sessiontask->save();

            return back()->with('error' ,'Väärä vastaus');
        }
        catch (QueryException $e){
            // Palautetaan edellinen tila
            $sess->sandboxedDB->restoreTables();
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
