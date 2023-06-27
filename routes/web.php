<?php

use App\Http\Controllers\BrevetController;
use App\Http\Controllers\ChercheurController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorantController;
use App\Http\Controllers\EquipeController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\MembresController;
use App\Http\Controllers\PartenaireController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//--Ather Routes--------------------------------------------------------------------------------------------------

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/comptes/edit', [CompteController::class, 'update'])->middleware(['auth', 'verified'])->name('comptes.edit');


//--Admin Routes--------------------------------------------------------------------------------------------------

Route::get('/inscriptions', [InscriptionController::class, 'index'])->middleware(['auth', 'verified', 'admin'])->name('inscriptions.index');
Route::delete('/inscriptions/{inscription}', [InscriptionController::class, 'destroy'])->middleware(['auth', 'verified', 'admin'])->name('inscriptions.destroy');
Route::get('/membres', [MembresController::class, 'index'])->middleware(['auth', 'admin', 'verified'])->name('membres.index');
Route::get('/equipes', [EquipeController::class, 'index'])->middleware(['auth', 'admin', 'verified'])->name('equipes.index');
Route::get('/projets', [ProjetController::class, 'index'])->middleware(['auth', 'admin', 'verified'])->name('projets.index');
Route::post('/equipes/store', [EquipeController::class, 'store'])->middleware(['auth', 'admin', 'verified'])->name('equipes.store');
Route::delete('/equipes/{equipe}', [EquipeController::class, 'destroy'])->middleware(['auth', 'verified', 'admin'])->name('equipes.destroy');
Route::post('/equipes/edit', [EquipeController::class, 'update'])->middleware(['auth', 'admin', 'verified'])->name('equipes.edit');

Route::post('/comptes/store', [CompteController::class, 'store'])->middleware(['auth', 'admin', 'verified'])->name('comptes.store');
Route::post('/comptes/store/admin', [CompteController::class, 'store_admin'])->middleware(['auth', 'admin', 'verified'])->name('comptes.store.admin');
Route::post('/comptes/store/chercheur', [CompteController::class, 'store_chercheur'])->middleware(['auth', 'admin', 'verified'])->name('comptes.store.chercheur');
Route::post('/comptes/store/doctorant', [CompteController::class, 'store_doctorant'])->middleware(['auth', 'admin', 'verified'])->name('comptes.store.doctorant');
Route::post('/comptes/active', [CompteController::class, 'active_compte'])->middleware(['auth', 'admin', 'verified'])->name('comptes.active');
Route::post('/comptes/desactive', [CompteController::class, 'desactive_compte'])->middleware(['auth', 'admin', 'verified'])->name('comptes.desactive');
Route::delete('/comptes/chercheur/{chercheur}', [CompteController::class, 'destroy_chercheur'])->middleware(['auth', 'verified', 'admin'])->name('comptes.destroy.chercheur');
Route::delete('/comptes/doctorant/{doctorant}', [CompteController::class, 'destroy_doctorant'])->middleware(['auth', 'verified', 'admin'])->name('comptes.destroy.doctorant');

Route::post('/chercheurs/edit', [ChercheurController::class, 'update'])->middleware(['auth', 'admin', 'verified'])->name('chercheurs.edit');

Route::post('/doctorants/edit', [DoctorantController::class, 'update'])->middleware(['auth', 'admin', 'verified'])->name('doctorants.edit');

Route::get('/evenements/admin', [EvenementController::class, 'index'])->middleware(['auth', 'verified', 'admin'])->name('evenements.admin.index');
Route::post('/evenements/store', [EvenementController::class, 'store'])->middleware(['auth', 'verified', 'admin'])->name('evenements.store');
Route::delete('/evenements/{evenement}', [EvenementController::class, 'destroy'])->middleware(['auth', 'verified', 'admin'])->name('evenements.destroy');
Route::post('/evenements/edit', [EvenementController::class, 'update'])->middleware(['auth', 'admin', 'verified'])->name('evenements.edit');

Route::post('/projets/store', [ProjetController::class, 'store'])->middleware(['auth', 'verified', 'admin'])->name('projets.store');
Route::delete('/projets/{projet}', [ProjetController::class, 'destroy'])->middleware(['auth', 'verified', 'admin'])->name('projets.destroy');
Route::post('/projets/edit', [ProjetController::class, 'update'])->middleware(['auth', 'admin', 'verified'])->name('projets.edit');

Route::get('/brevet', [BrevetController::class, 'show'])->middleware(['auth', 'admin', 'verified'])->name('brevets.index');
Route::post('/brevets/store', [BrevetController::class, 'store'])->middleware(['auth', 'verified', 'admin'])->name('brevets.store');
Route::delete('/brevets/{brevet}', [BrevetController::class, 'destroy'])->middleware(['auth', 'verified', 'admin'])->name('brevets.destroy');
Route::post('/brevets/edit', [BrevetController::class, 'update'])->middleware(['auth', 'admin', 'verified'])->name('brevets.edit');

Route::get('/partenaires', [PartenaireController::class, 'index'])->middleware(['auth', 'admin', 'verified'])->name('partenaires.index');
Route::post('/partenaires/store', [PartenaireController::class, 'store'])->middleware(['auth', 'verified', 'admin'])->name('partenaires.store');
Route::delete('/partenaires/{partenaire}', [PartenaireController::class, 'destroy'])->middleware(['auth', 'verified', 'admin'])->name('partenaires.destroy');
Route::post('/partenaires/edit', [PartenaireController::class, 'update'])->middleware(['auth', 'admin', 'verified'])->name('partenaires.edit');

Route::get('/brevets', [BrevetController::class, 'index'])->middleware(['auth', 'admin', 'verified'])->name('brevets.index');
Route::post('/brevet/etat', [BrevetController::class, 'active'])->middleware(['auth', 'verified', 'admin'])->name('brevet.etat');
Route::get('/brevet/download', [BrevetController::class, 'download'])->middleware(['auth', 'verified', 'admin'])->name('brevet.download');

Route::post('/themes/store', [ThemeController::class, 'store'])->middleware(['auth', 'admin', 'verified'])->name('themes.store');

Route::get('/settings', [SettingsController::class, 'index'])->middleware(['auth', 'verified', 'admin'])->name('settings.index');
Route::post('/settings/edit', [SettingsController::class, 'update'])->middleware(['auth', 'verified', 'admin'])->name('settings.edit');






//--Chercheur Routes--------------------------------------------------------------------------------------------------------------

Route::get('/doctorants', [DoctorantController::class, 'index'])->middleware(['auth', 'chercheur', 'verified'])->name('doctorants.index');

Route::get('/evenements/chercheur', [EvenementController::class, 'index'])->middleware(['auth', 'verified', 'chercheur'])->name('evenements.chercheur.index');

Route::get('/equipe', [EquipeController::class, 'show'])->middleware(['auth', 'chercheur', 'verified'])->name('equipes.show');

Route::get('/projet', [ProjetController::class, 'show'])->middleware(['auth', 'chercheur', 'verified'])->name('projets.show');

Route::get('/conference/chercheur', [ConferenceController::class, 'show'])->middleware(['auth', 'chercheur', 'verified'])->name('conferences.chercheur.show');
Route::post('/conferences/chercheur/edit', [ConferenceController::class, 'update'])->middleware(['auth', 'chercheur', 'verified'])->name('conferences.chercheur.edit');
Route::post('/conferences/chercheur/store', [ConferenceController::class, 'store'])->middleware(['auth', 'verified', 'chercheur'])->name('conferences.chercheur.store');
Route::delete('/conferences/chercheur/{conference}', [ConferenceController::class, 'destroy'])->middleware(['auth', 'verified', 'chercheur'])->name('conferences.chercheur.destroy');

Route::post('/rapport/etat', [RapportController::class, 'active'])->middleware(['auth', 'verified', 'chercheur'])->name('rapport.etat');
Route::get('/doctorants/rapports', [RapportController::class, 'show'])->middleware(['auth', 'chercheur', 'verified'])->name('doctorants.rapports.show');
Route::get('/doctorants/rapport/download', [RapportController::class, 'download'])->middleware(['auth', 'chercheur', 'verified'])->name('rapport.download');

Route::get('/chercheurs/brevets', [BrevetController::class, 'show'])->middleware(['auth', 'chercheur', 'verified'])->name('brevets.show');
Route::post('/brevets/store', [BrevetController::class, 'store'])->middleware(['auth', 'verified', 'chercheur'])->name('brevets.store');
Route::delete('/brevets/{brevet}', [BrevetController::class, 'destroy'])->middleware(['auth', 'verified', 'chercheur'])->name('brevets.destroy');
Route::post('/brevets/edit', [BrevetController::class, 'update'])->middleware(['auth', 'verified', 'chercheur',])->name('brevets.edit');
Route::get('/brevet/download', [BrevetController::class, 'download'])->middleware(['auth', 'verified', 'chercheur'])->name('brevet.download');


//--Doctorant--------------------------------------------------------------------------------------------------

Route::get('/evenements/doctorant', [EvenementController::class, 'index'])->middleware(['auth', 'verified', 'doctorant'])->name('evenements.doctorant.index');

Route::get('/rapport', [RapportController::class, 'show'])->middleware(['auth', 'doctorant', 'verified'])->name('rapports.show');

Route::get('/conference/doctorant', [ConferenceController::class, 'show'])->middleware(['auth', 'doctorant', 'verified'])->name('conferences.doctorant.show');
Route::post('/conferences/doctorant/store', [ConferenceController::class, 'store'])->middleware(['auth', 'verified', 'doctorant'])->name('conferences.doctorant.store');
Route::delete('/conferences/doctorant/{conference}', [ConferenceController::class, 'destroy'])->middleware(['auth', 'verified', 'doctorant'])->name('conferences.doctorant.destroy');
Route::post('/conferences/doctorant/edit', [ConferenceController::class, 'update'])->middleware(['auth', 'doctorant', 'verified'])->name('conferences.doctorant.edit');

Route::post('/rapports/store', [RapportController::class, 'store'])->middleware(['auth', 'verified', 'doctorant'])->name('rapports.store');
Route::delete('/rapports/{rapport}', [RapportController::class, 'destroy'])->middleware(['auth', 'verified', 'doctorant'])->name('rapports.destroy');
Route::post('/rapports/edit', [RapportController::class, 'update'])->middleware(['auth', 'doctorant', 'verified'])->name('rapports.edit');
Route::get('/rapport/download', [RapportController::class, 'download'])->middleware(['auth', 'verified', 'doctorant'])->name('rapport.download');

//----Inscription Routes-------------------------------------------------------------------------------------

Route::get('/inscriptions/create', [InscriptionController::class, 'create'])->name('inscriptions.create');
Route::post('/inscriptions/store', [InscriptionController::class, 'store'])->name('inscriptions.store');
Route::get('/successfulregistration', function () {return Inertia::render('Inscription/SuccessfulRegistration');})->name('successfulregistration');
Route::get('/failedregistration', function () {return Inertia::render('Inscription/FailedRegistration');})->name('failedregistration');





/*
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
*/







/*
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
*/


require __DIR__.'/auth.php';
