<?php

namespace App\Http\Controllers;

use App\Session;
use App\Task;
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

    public function r4() {
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('teacher')) {
            return back()->with('error', 'Ei oikeutta!');
        }

        $entries = [];

        $taskTimes = DB::table('tasks')
            ->select(DB::raw('tasks.id AS task_id, AVG(sessiontasks.finished_at-sessiontasks.created_at) AS avg'))
            ->join('sessiontasks', 'tasks.id', '=', 'sessiontasks.task_id')
            ->groupBy('tasks.id')
            ->orderByDesc('avg')
            ->get();

        foreach ($taskTimes as $row) {
            // Laske montako yritystä tehtävään on keskimäärin tarvittu ennen oikeaa tulosta
            $task = Task::find($row->task_id);
            $sessiontasks = $task->sessiontasks()->get();
            $avgAttempts = 0.0;
            $totalFailureCount = 0.0;
            foreach ($sessiontasks as $sessiontask) {
                if ($sessiontask->correct) {
                    $avgAttempts += $sessiontask->taskattempts()->count();
                } else if (!$sessiontask->correct && $sessiontask->taskattempts()->count() == 3) {
                    $totalFailureCount += 1;
                }
            }
            if (count($sessiontasks) > 0) {
                $avgAttempts /= floatval(count($sessiontasks));
            }

            // Laske prosenttiosuus siitä, kuinka usein tehtävä jäi kokonaan ratkaisematta
            $totalFailurePercentage = 0;
            if ($totalFailureCount > 0) {
                $totalFailurePercentage = round(($totalFailureCount / count($sessiontasks)) * 100, 2);
            }

            $entries[] = [
                'task_id' => $task->id,
                'avg_time' => $row->avg,
                'avg_attempts' => $avgAttempts,
                'failure_per' => $totalFailurePercentage
            ];
        }

        return view('reports.r4', [
            'page_name' => 'Raportti 4 - Tehtävälistaus vaikeusjärjestyksessä',
            'data' => $entries
        ]);
    }

    public function r5() {
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('teacher')) {
            return back()->with('error', 'Ei oikeutta!');
        }

        $types = [
            'select', 'update', 'insert', 'delete'
        ];

        $entries = [];
        foreach ($types as $type) {
            $entries[$type] = [
                'avg_time' => $this->getAvgAttemptTimeForType($type),
                'avg_attempts' => $this->getAvgAttemptCountForType($type)
            ];
        }

        return view('reports.r5', [
            'page_name' => 'Raportti 5 - Tyyppikohtaiset tilastot',
            'data' => $entries
        ]);
    }

    private function getAvgAttemptTimeForType($type) {
        $avgTime = DB::table('taskattempts')
            ->select(DB::raw('AVG(finished_at-created_at)'))
            ->whereIn('sessiontask_id',
                DB::table('sessiontasks')
                    ->select('id')
                    ->whereIn('task_id',
                        DB::table('tasks')
                            ->select('id')
                            ->where('type', $type)))
            ->first()->avg;
        if ($avgTime == null) return '00:00:00.0';
        return $avgTime;
    }

    private function getAvgAttemptCountForType($type) {
        $tasks = Task::where('type', $type)->get();
        $avgAttempts = 0.0;
        $tryCount = 0;
        foreach ($tasks as $task) {
            $sessiontasks = $task->sessiontasks()->get();
            foreach ($sessiontasks as $sessiontask) {
                $avgAttempts += $sessiontask->taskattempts()->count();
            }
            $tryCount += count($sessiontasks);
        }
        if ($tryCount == 0) return 0;
        return round($avgAttempts / $tryCount, 2);
    }

}
