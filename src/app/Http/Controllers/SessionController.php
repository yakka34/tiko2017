<?php

namespace App\Http\Controllers;

use App\Session;
use App\Sessiontask;
use App\Tasklist;
use App\Utils\SandboxedDatabase;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function start($tasklist){
        $user = Auth::user();
        $tasklist = Tasklist::find($tasklist);
        if ($user->can('solve-task') && $tasklist != null){

            $session = Session::create([
                'user_id' => $user->id,
                'tasklist_id' => $tasklist->id,
            ]);

            // Luo tietokantaan taulut tälle sessiolle
            $session->sandboxedDB = new SandboxedDatabase($session);
            $session->sandboxedDB->createTables();

            return redirect()->route('session.show.tasklist',$session->id);
        }
        return back()->with('error','Sinulla ei ole oikeutta tehdä tätä');
    }

    public function stop($session){
        $user = Auth::user();
        $session = Session::find($session);
        if ($session->user_id == $user->id && $session->finished_at == null) {

            // Tarkista, onko session kaikki tehtävät tehty
            $tasklist = Tasklist::where(['id' => $session->tasklist_id])->first();
            if ($tasklist == null) {
                throw new Exception('Session doesn\'t have a tasklist!');
            }
            $done_tasks = 0;
            foreach ($tasklist->tasks as $task) {
                $sessiontask = Sessiontask::where(['session_id' => $session->id, 'task_id' => $task->id])->first();
                if ($sessiontask != null) {
                    if ($sessiontask->finished_at != null) $done_tasks++;
                }
            }

            if ($done_tasks != count($tasklist->tasks)) {
                // Kaikkia tehtäviä ei ole tehty
                return back()->with('error', 'Kaikkia tehtäviä ei ole tehty!');
            }

            $session->finished_at = Carbon::now();
            $session->save();

            // Poista session tarvitsemat tietokantataulut
            $session->sandboxedDB = new SandboxedDatabase($session);
            $session->sandboxedDB->dropTables();

            return redirect()->route('home')->with('status', 'Sessio lopetettu');
        }
        return back()->with('error','Sinulla ei ole oikeutta tehdä tätä');
    }
}
