<?php

namespace App\Http\Controllers;

use App\Session;
use App\Tasklist;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    //
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
            return redirect(route('session.show.tasklist',$session->id))->with('status','Sessio luotu');
        }
        return back()->with('error','Sinulla ei ole oikeutta tehdä tätä');
    }

    public function stop($session){
        $user = Auth::user();
        $session = Session::find($session);
        if($session->user_id == $user->id && $session->finished_at == null){
            $session->finished_at = Carbon::now();
            $session->save();
            return "sessio lopetettu";
        }
        return back()->with('error','Sinulla ei ole oikeutta tehdä tätä');
    }
}