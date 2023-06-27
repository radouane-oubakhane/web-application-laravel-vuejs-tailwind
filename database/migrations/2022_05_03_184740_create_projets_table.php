<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projets', function (Blueprint $table) {
            $table->id();
            $table->string('intitule');
            $table->text('detail');
            $table->string('photo');
            $table->timestamps();
            $table->foreignId('theme_id')->constrained();
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
        Schema::dropIfExists('projets');
    }
}
