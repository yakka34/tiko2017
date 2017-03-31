<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tasks',function (Blueprint $table){
            $table->increments('id');
            $table->string('author');
            $table->text('description');
            //update,insert,delete jne.
            $table->string('type');
            $table->date('date');
            $table->string('answer');
        });

        Schema::create('tasklists',function (Blueprint $table){
            $table->increments('id');
            $table->string('author');
            $table->date('date');
            $table->text('description');
        });

        Schema::create('task_tasklist',function (Blueprint $table){
            $table->integer('task_id');
            $table->integer('tasklist_id');
            $table->unique(['task_id','tasklist_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropifExists('tasks');
        Schema::dropifExists('tasklists');
        Schema::dropIfExists('task_tasklist');
    }
}
