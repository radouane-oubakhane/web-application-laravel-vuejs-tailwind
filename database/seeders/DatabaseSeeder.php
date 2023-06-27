<?php

namespace Database\Seeders;

use App\Models\Brevet;
use App\Models\Chercheur;
use App\Models\Conference;
use App\Models\Doctorant;
use App\Models\Equipe;
use App\Models\Evenement;
use App\Models\Fonction;
use App\Models\Inscription;
use App\Models\LabInfo;
use App\Models\Partenaire;
use App\Models\Projet;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fonctions')->insert([
            'intitule' => 'doctorant'
        ]);
        DB::table('fonctions')->insert([
            'intitule' => 'chercheur'
        ]);

        /*DB::table('themes')->insert([
            'intitule' => 'JEE',
            'detail' => 'a'
        ]);
        DB::table('themes')->insert([
            'intitule' => 'PHP',
            'detail' => 'b'
        ]);
        */


        User::factory(1)->create();
        LabInfo::factory(1)->create();
        Inscription::factory(20)->create();








    }
}
