<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPivotTableForTodoAndFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todo_file', function (Blueprint $table) {
            $table->unsignedBigInteger('todo_id');
            $table->foreign('todo_id')->references('id')->on('todos')->onDelete('cascade');
            $table->unsignedBigInteger('file_id');
            $table->foreign('file_id')->references('id')->on('file_upload')->onDelete('cascade');
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
    }
}
