<?php

use App\Sessiontask;
use App\Task;
use App\Taskattempt;
use App\Tasklist;
use Illuminate\Database\Seeder;

class SessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        /* "Järjestelmän pitää olla testattu ja sen pitää sisältää testiaineisto joka, koostuu kahdesta testisarjasta,
           jotka toteuttavat liitteen tehtävälistat. Molemmista testisarjoista on oltava simuloituna vähintään kolme
           sessiota, jotka sisältävät sekä onnistumisia, että epäonnistumisia." */

        // Kysely 1
        $task1 = Task::create([
            'description' => 'Valitse opettajien nimet.',
            'type' => 'select',
            'user_id' => 1, // Testiopettajan ID
            'answer' => "SELECT opettaja FROM kurssit;"
        ]);

        // Kysely 2
        $task2 = Task::create([
            'description' => 'Valitse opiskelijoiden nimet, joilla pääaineena on \'TKO\'.',
            'type' => 'select',
            'user_id' => 1, // Testiopettajan ID
            'answer' => "SELECT nimi FROM opiskelijat WHERE p_aine = 'TKO';"
        ]);

        // Kysely 3
        $task3 = Task::create([
            'description' => 'Mitkä ovat \'Villen\' suorittamien kurssien arvosanat?',
            'type' => 'select',
            'user_id' => 1, // Testiopettajan ID
            'answer' => "SELECT suoritukset.arvosana FROM opiskelijat, suoritukset WHERE opiskelijat.nro = suoritukset.op_nro AND opiskelijat.nimi = 'Ville';"
        ]);

        // Kysely 4
        $task4 = Task::create([
            'description' => 'Lisää opiskelija nimeltä Matti tietokantaan. Matin opiskelijanumero on 1234 ja pääaine VT.',
            'type' => 'insert',
            'user_id' => 1, // Testiopettajan ID
            'answer' => "INSERT INTO opiskelijat VALUES(1234, 'Matti', 'VT');"
        ]);

        // Kysely 5
        $task5 = Task::create([
            'description' => 'Poista opiskelija, jonka numero on 1234.',
            'type' => 'delete',
            'user_id' => 1, // Testiopettajan ID
            'answer' => "DELETE FROM opiskelijat WHERE nro = 1234;"
        ]);

        // Luo tehtävälistat
        $tasklist1 = Tasklist::create([
            'description' => 'Kyselysarja 1, kyselyt 1-3',
            'user_id' => 1
        ]);

        $tasklist2 = Tasklist::create([
            'description' => 'Kyselysarja 2, kyselyt 1, 4 ja 5',
            'user_id' => 1
        ]);

        // Lisää tehtävät listoihin
        $tasklist1->tasks()->attach($task1);
        $tasklist1->tasks()->attach($task2);
        $tasklist1->tasks()->attach($task3);

        $tasklist2->tasks()->attach($task1);
        $tasklist2->tasks()->attach($task4);
        $tasklist2->tasks()->attach($task5);

        $tasklist1->save();
        $tasklist2->save();

        // Luodaan testisessioita
        $sess1 = \App\Session::create([
            'user_id' => 2,     // 2 ja 3 ovat testiopiskelijoita
            'tasklist_id' => 1
        ]);
        $sess1->created_at = '2017-05-06 12:00:00';
        $sess1->finished_at = '2017-05-06 12:15:00';    // Kesto 15min
        $sess1->save();

        $sess2 = \App\Session::create([
            'user_id' => 2,     // 3 ja 4 ovat testiopiskelijoita
            'tasklist_id' => 1
        ]);
        $sess2->created_at = '2017-05-06 15:32:43';
        $sess2->finished_at = '2017-05-06 15:53:31';
        $sess2->save();

        $sess3 = \App\Session::create([
            'user_id' => 3,     // 3 ja 4 ovat testiopiskelijoita
            'tasklist_id' => 2
        ]);
        $sess3->created_at = '2017-05-06 13:10:00';
        $sess3->finished_at = '2017-05-06 13:15:00';    // Kesto 5min
        $sess3->save();

        // Luodaan sessioyrityksiä
        // Tehtävälista 1:n yritykset
        $try1 = Sessiontask::create([
            'correct' => true,
            'task_id' => 1,
            'session_id' => 1
        ]);
        $try1->created_at = '2017-05-06 12:00:30';
        $try1->finished_at = '2017-05-06 12:01:20';
        $try1->save();

        $try2 = Sessiontask::create([
            'correct' => false,
            'task_id' => 2,
            'session_id' => 1
        ]);
        $try2->created_at = '2017-05-06 12:01:20';
        $try2->finished_at = '2017-05-06 12:10:42';
        $try2->save();

        $try3 = Sessiontask::create([
            'correct' => true,
            'task_id' => 3,
            'session_id' => 1
        ]);
        $try3->created_at = '2017-05-06 12:10:42';
        $try3->finished_at = '2017-05-06 12:15:00';
        $try3->save();

        $try4 = Sessiontask::create([
            'correct' => true,
            'task_id' => 1,
            'session_id' => 2
        ]);
        $try4->created_at = '2017-05-06 15:32:43';
        $try4->finished_at = '2017-05-06 15:35:22';
        $try4->save();

        $try5 = Sessiontask::create([
            'correct' => true,
            'task_id' => 2,
            'session_id' => 2
        ]);
        $try5->created_at = '2017-05-06 15:35:22';
        $try5->finished_at = '2017-05-06 15:40:14';
        $try5->save();

        $try6 = Sessiontask::create([
            'correct' => true,
            'task_id' => 3,
            'session_id' => 2
        ]);
        $try6->created_at = '2017-05-06 15:40:14';
        $try6->finished_at = '2017-05-06 15:53:31';
        $try6->save();

        // Tehtävälista 2:n yritykset
        $try7 = Sessiontask::create([
            'correct' => true,
            'task_id' => 1,
            'session_id' => 3
        ]);
        $try7->created_at = '2017-05-06 13:10:00';
        $try7->finished_at = '2017-05-06 13:11:00';
        $try7->save();

        $try8 = Sessiontask::create([
            'correct' => true,
            'task_id' => 4,
            'session_id' => 3
        ]);
        $try8->created_at = '2017-05-06 13:11:00';
        $try8->finished_at = '2017-05-06 13:12:35';
        $try8->save();

        $try9 = Sessiontask::create([
            'correct' => false,
            'task_id' => 5,
            'session_id' => 3
        ]);
        $try9->created_at = '2017-05-06 13:12:35';
        $try9->finished_at = '2017-05-06 13:15:00';
        $try9->save();

        // Luodaan tehtäväsuorituksia
        $ta1 = Taskattempt::create([
            'answer' => 'SELECT opettaja FROM kurssit;',
            'sessiontask_id' => 1
        ]);
        $ta1->created_at = '2017-05-06 12:00:30';
        $ta1->finished_at = '2017-05-06 12:01:20';
        $ta1->save();

        $ta2 = Taskattempt::create([
            'answer' => 'DELETE FROM opiskelijat WHERE 1=1;',
            'sessiontask_id' => 2
        ]);
        $ta2->created_at = '2017-05-06 12:01:20';
        $ta2->finished_at = '2017-05-06 12:03:03';
        $ta2->save();

        $ta3 = Taskattempt::create([
            'answer' => 'DELETE FROM opiskelijat WHERE 1=1;',
            'sessiontask_id' => 2
        ]);
        $ta3->created_at = '2017-05-06 12:03:03';
        $ta3->finished_at = '2017-05-06 12:07:24';
        $ta3->save();

        $ta4 = Taskattempt::create([
            'answer' => 'DELETE FROM opiskelijat WHERE 1=1;',
            'sessiontask_id' => 2
        ]);
        $ta4->created_at = '2017-05-06 12:07:24';
        $ta4->finished_at = '2017-05-06 12:10:42';
        $ta4->save();

        $ta5 = Taskattempt::create([
            'answer' => 'SELECT suoritukset.arvosana FROM opiskelijat, suoritukset WHERE opiskelijat.nro = suoritukset.op_nro AND opiskelijat.nimi = \'Eetu\';',
            'sessiontask_id' => 3
        ]);
        $ta5->created_at = '2017-05-06 12:10:42';
        $ta5->finished_at = '2017-05-06 12:13:47';
        $ta5->save();

        $ta6 = Taskattempt::create([
            'answer' => 'SELECT suoritukset.arvosana FROM opiskelijat, suoritukset WHERE opiskelijat.nro = suoritukset.op_nro AND opiskelijat.nimi = \'Ville\';',
            'sessiontask_id' => 3
        ]);
        $ta6->created_at = '2017-05-06 12:13:47';
        $ta6->finished_at = '2017-05-06 12:15:00';
        $ta6->save();

        $ta7 = Taskattempt::create([
            'answer' => 'SELECT opettaja FROM kurssit;',
            'sessiontask_id' => 4
        ]);
        $ta7->created_at = '2017-05-06 15:32:43';
        $ta7->finished_at = '2017-05-06 15:35:22';
        $ta7->save();

        $ta8 = Taskattempt::create([
            'answer' => 'SELECT nimi FROM opiskelijat WHERE p_aine = \'TKO\';',
            'sessiontask_id' => 5
        ]);
        $ta8->created_at = '2017-05-06 15:35:22';
        $ta8->finished_at = '2017-05-06 15:40:14';
        $ta8->save();

        $ta9 = Taskattempt::create([
            'answer' => 'SELECT suoritukset.arvosana FROM opiskelijat, suoritukset WHERE opiskelijat.nro = suoritukset.op_nro AND opiskelijat.nimi = \'Ville\';',
            'sessiontask_id' => 6
        ]);
        $ta9->created_at = '2017-05-06 15:40:14';
        $ta9->finished_at = '2017-05-06 15:53:31';
        $ta9->save();

        $ta10 = Taskattempt::create([
            'answer' => 'SELECT opettaja FROM kurssit;',
            'sessiontask_id' => 7
        ]);
        $ta10->created_at = '2017-05-06 13:10:00';
        $ta10->finished_at = '2017-05-06 13:11:00';
        $ta10->save();

        $ta11 = Taskattempt::create([
            'answer' => 'INSERT INTO opiskelijat VALUES(1234, \'Matti\', \'VT\');',
            'sessiontask_id' => 8
        ]);
        $ta11->created_at = '2017-05-06 13:11:00';
        $ta11->finished_at = '2017-05-06 13:12:35';
        $ta11->save();

        $ta12 = Taskattempt::create([
            'answer' => 'DELETE FROM opiskelijat WHERE nro = 42;',
            'sessiontask_id' => 9
        ]);
        $ta12->created_at = '2017-05-06 13:12:35';
        $ta12->finished_at = '2017-05-06 13:13:49';
        $ta12->save();

        $ta13 = Taskattempt::create([
            'answer' => 'DELETE FROM opiskelijat WHERE 1=1;',
            'sessiontask_id' => 9
        ]);
        $ta13->created_at = '2017-05-06 13:13:49';
        $ta13->finished_at = '2017-05-06 13:14:00';
        $ta13->save();

        $ta14 = Taskattempt::create([
            'answer' => 'DELETE FROM opiskelijat WHERE 1=2;',
            'sessiontask_id' => 9
        ]);
        $ta14->created_at = '2017-05-06 13:14:00';
        $ta14->finished_at = '2017-05-06 13:15:00';
        $ta14->save();

    }
}
