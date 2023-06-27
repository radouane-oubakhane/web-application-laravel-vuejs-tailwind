<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctorants', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('numero_telephone')->unique();
            $table->string('cin')->unique();
            $table->string('cne')->unique();
            $table->date('date_naissance');
            $table->string('lieux_naissance');
            $table->string('photo');
            $table->text('sujet');
            $table->timestamps();

            $table->unsignedBigInteger('chercheur_id')->nullable($value = true);
            $table->foreign('chercheur_id')->references('id')->on('chercheurs')
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
        Schema::dropIfExists('doctorants');
    }
}
