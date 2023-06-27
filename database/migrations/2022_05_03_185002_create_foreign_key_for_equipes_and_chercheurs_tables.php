<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeignKeyForEquipesAndChercheursTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('equipes', function (Blueprint $table) {
            $table->unsignedBigInteger('chercheur_id')->nullable($value = true);
            $table->foreign('chercheur_id')->references('id')->on('chercheurs')
                ->onDelete('SET NULL');
        });



        Schema::table('chercheurs', function (Blueprint $table) {
            $table->unsignedBigInteger('equipe_id')->nullable($value = true);
            $table->foreign('equipe_id')->references('id')->on('equipes')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('foreign_key_for_equipes_and_chercheurs_tables');
    }
}
