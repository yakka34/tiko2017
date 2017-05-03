<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
        $table->increments('id');
        $table->timestamp('finished_at')->nullable();
        $table->integer('user_id');
        $table->integer('tasklist_id');
        $table->timestamps();
    });
        Schema::create('sessiontasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id');
            $table->integer('session_id');
            $table->timestamps();
            $table->timestamp('finished_at')->nullable();
            $table->boolean('correct')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('sessiontasks');
    }
}
