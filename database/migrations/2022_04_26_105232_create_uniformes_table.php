<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniformesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uniformes', function (Blueprint $table) {
            $table->id();
            $table->string('dni', 8)->unique();
            $table->string('apn', 100)->unique();
            $table->char('sexo');        
            $table->string('talla_saco', 10);
            $table->string('talla_pantalon', 10);
            $table->string('talla_camisa', 100)->nullable();
            $table->boolean('recogido')->default(false);  
            $table->bigInteger('user_id');  

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uniformes');
    }
}
