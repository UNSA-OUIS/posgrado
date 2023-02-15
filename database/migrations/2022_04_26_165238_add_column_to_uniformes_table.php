<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToUniformesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('uniformes', function (Blueprint $table) {
            $table->tinyInteger('mesa')->after('id')->default(1);
            $table->string('talla_saco', 10)->nullable()->change();
            $table->string('talla_pantalon', 10)->nullable()->change();
            $table->string('talla_camisa', 10)->nullable()->change();
            $table->bigInteger('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('uniformes', function (Blueprint $table) {
            $table->dropColumn('mesa');
        });
    }
}
