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
            $table->unsignedInteger('user_id');
            $table->text('description');
            //update,insert,delete jne.
            $table->string('type');
            $table->timestamps();
            $table->string('answer');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        Schema::create('tasklists',function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->timestamps();
            $table->text('description');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
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
