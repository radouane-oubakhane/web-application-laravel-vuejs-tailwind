<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrevetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brevets', function (Blueprint $table) {
            $table->id();
            $table->string('intitule');
            $table->text('detail');
            $table->text('type');
            $table->text('fichier');
            $table->boolean('etat')->default(false);
            $table->timestamps();

            $table->foreignId('chercheur_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brevets');
    }
}
