<?php

namespace App\Utils;


use App\Session;
use Exception;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SandboxedDatabase {

    private $dbConn;    // Tietokantayhteys
    private $session;
    private $tables = ['opiskelijat', 'kurssit', 'suoritukset'];

    public function __construct(Session $session) {
        if (env('DB_TASK_CONNECTION', 'pgsql') == 'mysql') {
            throw new Exception('Currently unsupported database management system MySQL');
        }
        $this->session = $session;
        $this->dbConn = DB::connection('task_sandbox_'.env('DB_TASK_CONNECTION', 'pgsql'));
    }

    public function createTables() {
        // Luo tietokantataulut

        $user = $this->session->user_id;
        $tasklist = $this->session->tasklist_id;
        $sessid = $this->session->id;

        // Tietokantojen luonti on nyt vain kovakoodattu tähän, koska projektin vieminen pidemmälle menisi jo
        // kevyesti yli kurssin vaatimusten.
        $sql = [
            "CREATE TABLE _".$user."_".$sessid.'_'.$tasklist."_opiskelijat (nro INTEGER PRIMARY KEY, nimi VARCHAR(100), p_aine VARCHAR(100));",
            "CREATE TABLE _".$user."_".$sessid.'_'.$tasklist."_kurssit(id INTEGER PRIMARY KEY, nimi VARCHAR(100), opettaja VARCHAR(100));",
            "CREATE TABLE _".$user."_".$sessid.'_'.$tasklist."_suoritukset(k_id INTEGER NOT NULL REFERENCES _".$user."_".$sessid.'_'.$tasklist."_kurssit(id), op_nro INTEGER NOT NULL REFERENCES _".$user."_".$sessid.'_'.$tasklist."_opiskelijat(nro), arvosana INTEGER NOT NULL, PRIMARY KEY(k_id, op_nro));",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_opiskelijat(nro, nimi, p_aine) VALUES(1, 'Maija', 'TKO');",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_opiskelijat(nro, nimi, p_aine) VALUES(2, 'Ville', 'TKO');",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_opiskelijat(nro, nimi, p_aine) VALUES(3, 'Kalle', 'VT');",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_opiskelijat(nro, nimi, p_aine) VALUES(4, 'Liisa', 'VT');",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_kurssit(id, nimi, opettaja) VALUES(1, 'tkp', 'KI');",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_kurssit(id, nimi, opettaja) VALUES(2, 'oope', 'JL');",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_kurssit(id, nimi, opettaja) VALUES(3, 'tiko', 'MJ');",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_suoritukset(k_id, op_nro, arvosana) VALUES(1, 1, 5);",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_suoritukset(k_id, op_nro, arvosana) VALUES(1, 2, 4);",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_suoritukset(k_id, op_nro, arvosana) VALUES(1, 3, 2);",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_suoritukset(k_id, op_nro, arvosana) VALUES(2, 1, 5);",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_suoritukset(k_id, op_nro, arvosana) VALUES(2, 2, 3);",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_suoritukset(k_id, op_nro, arvosana) VALUES(2, 4, 3);",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_suoritukset(k_id, op_nro, arvosana) VALUES(3, 1, 5);",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_suoritukset(k_id, op_nro, arvosana) VALUES(3, 2, 4);",

            "CREATE TABLE _".$user."_".$sessid.'_'.$tasklist."_check_opiskelijat (nro INTEGER PRIMARY KEY, nimi VARCHAR(100), p_aine VARCHAR(100));",
            "CREATE TABLE _".$user."_".$sessid.'_'.$tasklist."_check_kurssit(id INTEGER PRIMARY KEY, nimi VARCHAR(100), opettaja VARCHAR(100));",
            "CREATE TABLE _".$user."_".$sessid.'_'.$tasklist."_check_suoritukset(k_id INTEGER NOT NULL REFERENCES _".$user."_".$sessid.'_'.$tasklist."_check_kurssit(id), op_nro INTEGER NOT NULL REFERENCES _".$user."_".$sessid.'_'.$tasklist."_check_opiskelijat(nro), arvosana INTEGER NOT NULL, PRIMARY KEY(k_id, op_nro));",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_check_opiskelijat(nro, nimi, p_aine) VALUES(1, 'Maija', 'TKO');",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_check_opiskelijat(nro, nimi, p_aine) VALUES(2, 'Ville', 'TKO');",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_check_opiskelijat(nro, nimi, p_aine) VALUES(3, 'Kalle', 'VT');",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_check_opiskelijat(nro, nimi, p_aine) VALUES(4, 'Liisa', 'VT');",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_check_kurssit(id, nimi, opettaja) VALUES(1, 'tkp', 'KI');",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_check_kurssit(id, nimi, opettaja) VALUES(2, 'oope', 'JL');",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_check_kurssit(id, nimi, opettaja) VALUES(3, 'tiko', 'MJ');",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_check_suoritukset(k_id, op_nro, arvosana) VALUES(1, 1, 5);",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_check_suoritukset(k_id, op_nro, arvosana) VALUES(1, 2, 4);",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_check_suoritukset(k_id, op_nro, arvosana) VALUES(1, 3, 2);",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_check_suoritukset(k_id, op_nro, arvosana) VALUES(2, 1, 5);",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_check_suoritukset(k_id, op_nro, arvosana) VALUES(2, 2, 3);",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_check_suoritukset(k_id, op_nro, arvosana) VALUES(2, 4, 3);",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_check_suoritukset(k_id, op_nro, arvosana) VALUES(3, 1, 5);",
            "INSERT INTO _".$user."_".$sessid.'_'.$tasklist."_check_suoritukset(k_id, op_nro, arvosana) VALUES(3, 2, 4);"];

        foreach ($sql as $row) {
            $this->dbConn->statement($row);
        }
        $this->dbConn->commit();

        return $this;
    }

    public function runSelect($sql, $check) {
        $sql = $this->fixSQL($sql, $check);
        return $this->dbConn->select($sql);
    }

    public function runUpdate($sql, $check) {
        $sql = $this->fixSQL($sql, $check);
        return $this->dbConn->update($sql);
    }

    public function runInsert($sql, $check) {
        $sql = $this->fixSQL($sql, $check);
        return $this->dbConn->insert($sql);
    }

    public function runDelete($sql, $check) {
        $sql = $this->fixSQL($sql, $check);
        return $this->dbConn->delete($sql);
    }

    private function fixSQL($sql, $check) {
        foreach ($this->tables as $table) {
            $newName = '_'.$this->session->user_id.'_'.$this->session->id.'_'.$this->session->tasklist_id.'_'.($check ? 'check_' : '').$table;
            $sql = str_replace($table, $newName, $sql);
        }
        return $sql;
    }

    public function dropTables() {
        $user = $this->session->user_id;
        $tasklist = $this->session->tasklist_id;
        $sessid = $this->session->id;
        $dbType = env('DB_TASK_CONNECTION', 'pgsql');
        if ($dbType == 'mysql') {
            $this->dbConn->statement('SET FOREIGN_KEY_CHECKS=0');
        }
        foreach ($this->tables as $table) {
            $this->dbConn->statement('DROP TABLE _'.$user.'_'.$sessid.'_'.$tasklist.'_'.$table . ($dbType == 'pgsql' ? ' CASCADE' : ''));
            $this->dbConn->statement('DROP TABLE _'.$user.'_'.$sessid.'_'.$tasklist.'_check_'.$table . ($dbType == 'pgsql' ? ' CASCADE' : ''));
        }
        if ($dbType == 'mysql') {
            $this->dbConn->statement('SET FOREIGN_KEY_CHECKS=1');
        }
        $this->dbConn->commit();
    }

    public function backupTables() {
        if (env('DB_TASK_CONNECTION', 'pgsql') == 'mysql') {
            throw new Exception('Currently unsupported database management system MySQL');
        }
        $user = env('DB_TASK_USERNAME', 'homestead');
        $pass = env('DB_TASK_PASSWORD', '');
        $host = env('DB_TASK_HOST', '127.0.0.1');
        $port = env('DB_TASK_PORT', '5432');
        $dbname = env('DB_TASK_DATABASE', 'homestead');

        $uid = $this->session->user_id;
        $sessid = $this->session->id;
        $tasklistid = $this->session->tasklist_id;
        $dump_filename = 'dump_'.$uid.'_'.$sessid.'_'.$tasklistid.'.sql';
        $dump_file = storage_path('app/sandbox_dumps/'.$dump_filename);

        $cmd = 'PGPASSWORD="'.$pass.'" pg_dump -t _'.$uid.'_'.$sessid.'_'.$tasklistid.'_* -f '.$dump_file.' -U '.$user.' -h '.$host.' -p '.$port.' '.$dbname;
        $retval = 0;
        $out = [];
        exec($cmd, $out, $retval);
        if ($retval != 0) {
            Log::debug('Command: '.$cmd);
            Log::error(implode('\n', $out));
            throw new Exception('Database backup failed with error '.$retval.'!');
        }
    }

    public function restoreTables() {
        $uid = $this->session->user_id;
        $sessid = $this->session->id;
        $tasklistid = $this->session->tasklist_id;

        $user = env('DB_TASK_USERNAME', 'homestead');
        $pass = env('DB_TASK_PASSWORD', '');
        $host = env('DB_TASK_HOST', '127.0.0.1');
        $port = env('DB_TASK_PORT', '5432');
        $dbname = env('DB_TASK_DATABASE', 'homestead');

        $dump_filename = 'dump_'.$uid.'_'.$sessid.'_'.$tasklistid.'.sql';
        $dump_filepath = storage_path('app/sandbox_dumps/'.$dump_filename);

        // Drop tables first
        $this->dropTables();

        $cmd = 'PGPASSWORD="'.$pass.'" psql -q -h '.$host.' -p '.$port.' -U '.$user.' -d '.$dbname.' -f '.$dump_filepath;

        $out = [];
        $retval = 0;
        exec($cmd, $out, $retval);

        Log::debug('Database restore completed with code '.$retval);

        if ($retval != 0) {
            throw new Exception('Database restoring failed with error '.$retval);
        }
    }

}