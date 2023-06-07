<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProgramasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_programas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->char('nues', 3);
            $table->char('espe', 1)->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'nues', 'espe']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_programas');
    }
}
