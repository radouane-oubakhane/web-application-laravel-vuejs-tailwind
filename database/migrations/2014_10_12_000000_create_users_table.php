<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('photo');
            $table->string('password');
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_chercheur')->default(false);
            $table->boolean('is_doctorant')->default(false);
            $table->rememberToken();
            $table->timestamps();

            $table->integer('userable_id')->nullable($value = true);
            $table->string('userable_type')->nullable($value = true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
