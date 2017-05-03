<?php

namespace App\Utils;


use App\Session;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;

class SandboxedDatabase {

    private $dbConn;    // Tietokantayhteys
    private $session;
    private $tables = ['opiskelijat', 'kurssit', 'suoritukset'];

    public function __construct(Session $session) {
        $this->session = $session;
        $this->dbConn = DB::connection('task_sandbox_mysql');
    }

    public function createTables() {
        // Luo tietokantataulut

        $user = $this->session->user_id;
        $tasklist = $this->session->tasklist_id;

        // Tietokantojen luonti on nyt vain kovakoodattu tähän, koska projektin vieminen pidemmälle menisi jo
        // kevyesti yli kurssin vaatimusten.
        $sql = [
            "CREATE TABLE ".$user."_".$tasklist."_opiskelijat (nro INTEGER PRIMARY KEY, nimi VARCHAR(100), p_aine VARCHAR(100));",
            "CREATE TABLE ".$user."_".$tasklist."_kurssit(id INTEGER PRIMARY KEY, nimi VARCHAR(100), opettaja VARCHAR(100));",
            "CREATE TABLE ".$user."_".$tasklist."_suoritukset(k_id INTEGER NOT NULL REFERENCES ".$user."_".$tasklist."_kurssit(id), op_nro INTEGER NOT NULL REFERENCES ".$user."_".$tasklist."_opiskelijat(nro), arvosana INTEGER NOT NULL, PRIMARY KEY(k_id, op_nro));",
            "INSERT INTO ".$user."_".$tasklist."_opiskelijat(nro, nimi, p_aine) VALUES(1, 'Maija', 'TKO');",
            "INSERT INTO ".$user."_".$tasklist."_opiskelijat(nro, nimi, p_aine) VALUES(2, 'Ville', 'TKO');",
            "INSERT INTO ".$user."_".$tasklist."_opiskelijat(nro, nimi, p_aine) VALUES(3, 'Kalle', 'VT');",
            "INSERT INTO ".$user."_".$tasklist."_opiskelijat(nro, nimi, p_aine) VALUES(4, 'Liisa', 'VT');",
            "INSERT INTO ".$user."_".$tasklist."_kurssit(id, nimi, opettaja) VALUES(1, 'tkp', 'KI');",
            "INSERT INTO ".$user."_".$tasklist."_kurssit(id, nimi, opettaja) VALUES(2, 'oope', 'JL');",
            "INSERT INTO ".$user."_".$tasklist."_kurssit(id, nimi, opettaja) VALUES(3, 'tiko', 'MJ');",
            "INSERT INTO ".$user."_".$tasklist."_suoritukset(k_id, op_nro, arvosana) VALUES(1, 1, 5);",
            "INSERT INTO ".$user."_".$tasklist."_suoritukset(k_id, op_nro, arvosana) VALUES(1, 2, 4);",
            "INSERT INTO ".$user."_".$tasklist."_suoritukset(k_id, op_nro, arvosana) VALUES(1, 3, 2);",
            "INSERT INTO ".$user."_".$tasklist."_suoritukset(k_id, op_nro, arvosana) VALUES(2, 1, 5);",
            "INSERT INTO ".$user."_".$tasklist."_suoritukset(k_id, op_nro, arvosana) VALUES(2, 2, 3);",
            "INSERT INTO ".$user."_".$tasklist."_suoritukset(k_id, op_nro, arvosana) VALUES(2, 4, 3);",
            "INSERT INTO ".$user."_".$tasklist."_suoritukset(k_id, op_nro, arvosana) VALUES(3, 1, 5);",
            "INSERT INTO ".$user."_".$tasklist."_suoritukset(k_id, op_nro, arvosana) VALUES(3, 2, 4);",

            "CREATE TABLE ".$user."_".$tasklist."_check_opiskelijat (nro INTEGER PRIMARY KEY, nimi VARCHAR(100), p_aine VARCHAR(100));",
            "CREATE TABLE ".$user."_".$tasklist."_check_kurssit(id INTEGER PRIMARY KEY, nimi VARCHAR(100), opettaja VARCHAR(100));",
            "CREATE TABLE ".$user."_".$tasklist."_check_suoritukset(k_id INTEGER NOT NULL REFERENCES ".$user."_".$tasklist."_check_kurssit(id), op_nro INTEGER NOT NULL REFERENCES ".$user."_".$tasklist."_check_opiskelijat(nro), arvosana INTEGER NOT NULL, PRIMARY KEY(k_id, op_nro));",
            "INSERT INTO ".$user."_".$tasklist."_check_opiskelijat(nro, nimi, p_aine) VALUES(1, 'Maija', 'TKO');",
            "INSERT INTO ".$user."_".$tasklist."_check_opiskelijat(nro, nimi, p_aine) VALUES(2, 'Ville', 'TKO');",
            "INSERT INTO ".$user."_".$tasklist."_check_opiskelijat(nro, nimi, p_aine) VALUES(3, 'Kalle', 'VT');",
            "INSERT INTO ".$user."_".$tasklist."_check_opiskelijat(nro, nimi, p_aine) VALUES(4, 'Liisa', 'VT');",
            "INSERT INTO ".$user."_".$tasklist."_check_kurssit(id, nimi, opettaja) VALUES(1, 'tkp', 'KI');",
            "INSERT INTO ".$user."_".$tasklist."_check_kurssit(id, nimi, opettaja) VALUES(2, 'oope', 'JL');",
            "INSERT INTO ".$user."_".$tasklist."_check_kurssit(id, nimi, opettaja) VALUES(3, 'tiko', 'MJ');",
            "INSERT INTO ".$user."_".$tasklist."_check_suoritukset(k_id, op_nro, arvosana) VALUES(1, 1, 5);",
            "INSERT INTO ".$user."_".$tasklist."_check_suoritukset(k_id, op_nro, arvosana) VALUES(1, 2, 4);",
            "INSERT INTO ".$user."_".$tasklist."_check_suoritukset(k_id, op_nro, arvosana) VALUES(1, 3, 2);",
            "INSERT INTO ".$user."_".$tasklist."_check_suoritukset(k_id, op_nro, arvosana) VALUES(2, 1, 5);",
            "INSERT INTO ".$user."_".$tasklist."_check_suoritukset(k_id, op_nro, arvosana) VALUES(2, 2, 3);",
            "INSERT INTO ".$user."_".$tasklist."_check_suoritukset(k_id, op_nro, arvosana) VALUES(2, 4, 3);",
            "INSERT INTO ".$user."_".$tasklist."_check_suoritukset(k_id, op_nro, arvosana) VALUES(3, 1, 5);",
            "INSERT INTO ".$user."_".$tasklist."_check_suoritukset(k_id, op_nro, arvosana) VALUES(3, 2, 4);"];

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
            $newName = $this->session->user_id.'_'.$this->session->tasklist_id.'_'.($check ? 'check_' : '').$table;
            $sql = str_replace($table, $newName, $sql);
        }
        return $sql;
    }

    public function dropTables() {
        $user = $this->session->user_id;
        $tasklist = $this->session->tasklist_id;
        $this->dbConn->statement('SET FOREIGN_KEY_CHECKS=0');
        foreach ($this->tables as $table) {
            $this->dbConn->statement('DROP TABLE '.$user.'_'.$tasklist.'_'.$table);
            $this->dbConn->statement('DROP TABLE '.$user.'_'.$tasklist.'_check_'.$table);
        }
        $this->dbConn->statement('SET FOREIGN_KEY_CHECKS=1');
        $this->dbConn->commit();
    }

}