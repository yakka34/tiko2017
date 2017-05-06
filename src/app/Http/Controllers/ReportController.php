<?php

namespace App\Http\Controllers;

use App\Session;
use App\Tasklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function r1() {
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('teacher')) {
            return back()->with('error', 'Ei oikeutta!');
        }

        $entries = [];

        $sessions = Session::whereNotNull('finished_at')->get();

        foreach ($sessions as $session) {
            $entries[] = [
                'session_id' => $session->id,
                'user' => $session->user()->first(),
                'successful_tasks' => count($session->sessiontasks()->where('correct', true)->get())
            ];
        }

        return view('reports.r1', [
            'page_name' => 'Raportti 1 - Suoritettujen sessioiden tiedot',
            'data' => $entries
        ]);
    }

    public function r2() {
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('teacher')) {
            return back()->with('error', 'Ei oikeutta!');
        }

        $entries = [];
        $tasklists = Tasklist::all();

        foreach ($tasklists as $tasklist) {
            $stats = DB::table('sessions')->select(DB::raw('MIN(finished_at-created_at), MAX(finished_at-created_at), AVG(finished_at-created_at)'))->where('tasklist_id', $tasklist->id)->first();
            $entries[] = [
                'tasklist_id' => $tasklist->id,
                'fastest' => $stats->min,
                'slowest' => $stats->max,
                'average' => $stats->avg
            ];
        }

        return view('reports.r2', [
            'page_name' => 'Raportti 2 - Tehtävälistakohtainen suoritusaika',
            'data' => $entries
        ]);
    }

    public function r3() {
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('teacher')) {
            return back()->with('error', 'Ei oikeutta!');
        }

        $entries = [];
        $tasklists = Tasklist::all();

        foreach ($tasklists as $tasklist) {
            $entry = [
                'tasklist_id' => $tasklist->id
            ];
            $taskEntries = [];
            $tasks = $tasklist->tasks()->get();
            foreach ($tasks as $task) {
                $sessiontasks = $task->sessiontasks();
                $totalTaskCount = $sessiontasks->get()->count();
                $correctTaskCount = $sessiontasks->where('correct', true)->count();

                $successPercentage = round((floatval($correctTaskCount) / floatval($totalTaskCount)) * 100.0, 2);
                $avgTimeTook = DB::table('sessiontasks')->select(DB::raw('AVG(finished_at-created_at)'))->where('task_id', $task->id)->first()->avg;

                $taskEntries[] = [
                    'task_id' => $task->id,
                    'success_per' => $successPercentage,
                    'average_time' => $avgTimeTook
                ];
            }
            $entry['tasks'] = $taskEntries;
            $entries[] = $entry;
        }

        return view('reports.r3', [
            'page_name' => 'Raportti 3 - Tehtävälistakohtainen yhteenveto',
            'data' => $entries
        ]);

    }

}
