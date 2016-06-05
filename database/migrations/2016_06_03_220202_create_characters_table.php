<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCharactersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->integer('user_id')->unsigned();
            $table->string('name');
            $table->string('owner');
            $table->string('refresh_token');
            $table->enum('monitored', ['yes', 'no'])->default('no');
            $table->enum('status', ['online', 'offline'])->default('offline');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('characters');
    }
}
