<?php

namespace App\Http\Controllers;

use App\Mail\AccountStatusMail;
use App\Mail\CreateAccountMail;
use App\Mail\DeleteAccountMail;
use App\Mail\PassMail;
use App\Models\Chercheur;
use App\Models\Doctorant;
use App\Models\Inscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PHPUnit\Exception;
use function Symfony\Component\String\u;
use function Symfony\Component\Translation\t;

class CompteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *

     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $inscription = Inscription::all()->find($request->id);
            $pass = Str::random(9);
            if($inscription->fonction_id === 2)
            {
                Chercheur::create(
                    [
                        'nom' => $inscription->nom,
                        'prenom' => $inscription->prenom,
                        'email' => $inscription->email,
                        'numero_telephone' => $inscription->numero_telephone,
                        'cin' => $inscription->cin,
                        'date_naissance' => $inscription->date_naissance,
                        'lieux_naissance' => $inscription->lieux_naissance,
                        'photo' => $inscription->photo,
                        'equipe_id' => $request->equipe
                    ]
                );
                $chercheur_id = Chercheur::where('cin', $inscription->cin)->get()[0]->id;
                $chercheur = Chercheur::all()->find($chercheur_id);
                User::create(
                    [
                        'name' => $chercheur->nom,
                        'email' => $chercheur->email,
                        'password' => Hash::make($pass),
                        'is_chercheur' => true,
                        'photo' => $chercheur->photo,
                        'userable_id' => $chercheur->id,
                        'userable_type' => '\App\Models\Chercheur',
                        'email_verified_at' => now(),
                        'remember_token' => Str::random(10),
                    ]
                );
            }

            elseif($inscription->fonction_id === 1)
            {
                Doctorant::create(
                    [
                        'nom' => $inscription->nom,
                        'prenom' => $inscription->prenom,
                        'email' => $inscription->email,
                        'numero_telephone' => $inscription->numero_telephone,
                        'cin' => $inscription->cin,
                        'cne' => $inscription->cne,
                        'date_naissance' => $inscription->date_naissance,
                        'lieux_naissance' => $inscription->lieux_naissance,
                        'photo' => $inscription->photo,
                        'chercheur_id' => $request->encadrant,
                        'sujet' => $request->sujet
                    ]
                );
                $doctorant_id = Doctorant::where('cin', $inscription->cin)->get()[0]->id;
                $doctorant = Doctorant::all()->find($doctorant_id);
                User::create(
                    [
                        'name' => $doctorant->nom,
                        'email' => $doctorant->email,
                        'password' => Hash::make($pass),
                        'is_doctorant' => true,
                        'photo' => $doctorant->photo,
                        'userable_id' => $doctorant->id,
                        'userable_type' => '\App\Models\Doctorant',
                        'email_verified_at' => now(),
                        'remember_token' => Str::random(10),
                    ]
                );
            }

            try {
                Mail::to($inscription->email)->send(new PassMail($inscription, $pass, true));
            } catch (\Exception $e) {}

            $inscription->delete();
            DB::commit();
            return Redirect::route('membres.index')
                ->with('message', 'Le compte créé avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::route('inscriptions.index')
            ->with('error', 'L\'opération a échoué');
        }
    }

    public function store_admin(Request $request)
    {
        try {
            $filename = time() . $request->name . '.' . $request->photo->extension();
            $path = $request->file('photo')->storeAs(
                'users',
                $filename,
                'public'
            );

            $validated = $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email:rfc,dns',
                'photo' => 'required|image|mimes:jpg,png,jpeg,gif,svg'
            ]);

            $password = Str::random(9);
            User::create(
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'photo' => 'storage/' . $path,
                    'is_admin' => true,
                    'password' => Hash::make($password),
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );

            try {
                Mail::to($request->email)->send(new CreateAccountMail($request->name, $password, $request->email));
            } catch (\Exception $e) {}

            return Redirect::route('inscriptions.index')
                ->with('message', 'Le compte Admin créé avec succès');
        } catch (\Exception $e) {
            return Redirect::route('inscriptions.index')
                ->with('error', 'L\'opération a échoué');
        }
    }

    public function store_chercheur(Request $request)
    {
        try {
            DB::beginTransaction();
            $filename = time() . $request->cin . '.' . $request->photo->extension();
            $path = $request->file('photo')->storeAs(
                'users',
                $filename,
                'public'
            );

            $validated = $request->validate([
                'nom' => 'required|max:255',
                'prenom' => 'required|max:255',
                'email' => 'required|email:rfc,dns',
                'numero_telephone' => 'required|numeric|digits:10',
                'date_naissance' => 'required|date',
                'lieux_naissance' => 'required|max:255',
                'cin' => 'required|max:255',
                'photo' => 'required|image|mimes:jpg,png,jpeg,gif,svg'
            ]);

            Chercheur::create(
                [
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                    'email' => $request->email,
                    'numero_telephone' => $request->numero_telephone,
                    'cin' => $request->cin,
                    'date_naissance' => $request->date_naissance,
                    'lieux_naissance' => $request->lieux_naissance,
                    'photo' => 'storage/' . $path,
                ]
            );
            $chercheur_id = Chercheur::where('cin', $request->cin)->get()[0]->id;
            $chercheur = Chercheur::all()->find($chercheur_id);
            $password = Str::random(9);
            User::create(
                [
                    'name' => $chercheur->nom,
                    'email' => $chercheur->email,
                    'password' => Hash::make($password),
                    'is_chercheur' => true,
                    'photo' => 'storage/' . $path,
                    'userable_id' => $chercheur->id,
                    'userable_type' => '\App\Models\Chercheur',
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );
            DB::commit();

            try {
                Mail::to($request->email)->send(new CreateAccountMail($request->nom, $password, $request->email));
            } catch (\Exception $e) {}

            return Redirect::route('membres.index')
                ->with('message', 'Le compte Chercheur créé avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::route('membres.index')
                ->with('error', 'L\'opération a échoué');
        }
    }

    public function store_doctorant(Request $request)
    {
        try {
            DB::beginTransaction();
            $filename = time() . $request->cin . '.' . $request->photo->extension();
            $path = $request->file('photo')->storeAs(
                'users',
                $filename,
                'public'
            );

            $validated = $request->validate([
                'nom' => 'required|max:255',
                'prenom' => 'required|max:255',
                'email' => 'required|email:rfc,dns',
                'numero_telephone' => 'required|numeric|digits:10',
                'date_naissance' => 'required|date',
                'lieux_naissance' => 'required|max:255',
                'cin' => 'required|max:255',
                'cne' => 'required|max:255',
                'photo' => 'required|image|mimes:jpg,png,jpeg,gif,svg'
            ]);

            Doctorant::create(
                [
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                    'email' => $request->email,
                    'numero_telephone' => $request->numero_telephone,
                    'cin' => $request->cin,
                    'cne' => $request->cne,
                    'date_naissance' => $request->date_naissance,
                    'lieux_naissance' => $request->lieux_naissance,
                    'photo' => 'storage/' . $path,
                    'sujet' => $request->sujet
                ]
            );
            $doctorant_id = Doctorant::where('cin', $request->cin)->get()[0]->id;
            $doctorant = Doctorant::all()->find($doctorant_id);
            $password = Str::random(9);
            User::create(
                [
                    'name' => $doctorant->nom,
                    'email' => $doctorant->email,
                    'password' => Hash::make($password),
                    'is_doctorant' => true,
                    'photo' => 'storage/' . $path,
                    'userable_id' => $doctorant->id,
                    'userable_type' => '\App\Models\Doctorant',
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );
            DB::commit();

            try {
                Mail::to($request->email)->send(new CreateAccountMail($request->nom, $password, $request->email));
            } catch (\Exception $e) {}

            return Redirect::route('membres.index')
                ->with('message', 'Le compte Doctorant créé avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::route('membres.index')
                ->with('error', 'L\'opération a échoué');
        }
    }

    public function active_compte(Request $request)
    {
        try {
            $nom = '';
            if ($request->fonction === 2)
            {
                $user = User::where('userable_id', Chercheur::find($request->id)->id)
                    ->where('userable_type', '\App\Models\Chercheur')
                    ->get()[0];
                $user->is_chercheur = true;
                $user->is_doctorant = false;
                $nom = $user->name;
            }
            elseif($request->fonction === 1)
            {
                $user = User::where('userable_id', Doctorant::find($request->id)->id)
                    ->where('userable_type', '\App\Models\Doctorant')
                    ->get()[0];
                $user->is_doctorant = true;
                $user->is_chercheur = false;
                $nom = $user->name;
            }
            $user->is_admin = false;
            $user->save();

            try {
                Mail::to($user->email)->send(new AccountStatusMail($nom, true));
            } catch (\Exception $e) {}

            return Redirect::route('membres.index')
                ->with('message', 'Le compte a été activé avec succès');
        } catch (\Exception $e) {
            return Redirect::route('membres.index')
                ->with('error', 'L\'opération a échoué');
        }
    }

    public function desactive_compte(Request $request)
    {
        try {
            if ($request->fonction === 2)
            {
                $user = User::where('userable_id', Chercheur::find($request->id)->id)
                    ->where('userable_type', '\App\Models\Chercheur')
                    ->get()[0];
            }

            elseif($request->fonction === 1)
            {
                $user = User::where('userable_id', Doctorant::find($request->id)->id)
                    ->where('userable_type', '\App\Models\Doctorant')
                    ->get()[0];
            }
            $nom = $user->name;
            $user->is_admin = false;
            $user->is_chercheur = false;
            $user->is_doctorant = false;
            $user->save();

            try {
                Mail::to($user->email)->send(new AccountStatusMail($nom, false));
            } catch (\Exception $e) {}

            return Redirect::route('membres.index')
                ->with('message', 'Le compte a été désactivé avec succès');
        } catch (Exception $e) {
            return Redirect::route('membres.index')
                ->with('error', 'L\'opération a échoué');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *

     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();

            if ($user->is_admin or $user->is_chercheur or $user->is_doctorant)
            {
                if ($request->email) {
                    $validated = $request->validate([
                        'email' => 'required|email:rfc,dns',
                    ]);
                    $user->email = $request->email;
                }
                if ($request->numero_telephone) {
                    $validated = $request->validate([
                        'numero_telephone' => 'required|numeric|digits:10',
                    ]);
                    $user->numero_telephone = $request->numero_telephone;
                }
                if ($request->photo) {
                    $validated = $request->validate([
                        'photo' => 'required|image|mimes:jpg,png,jpeg,gif,svg'
                    ]);
                    Storage::delete('public'. substr($user->photo, 7));
                    $filename = time() . $user->name . '.' . $request->photo->extension();
                    $path = $request->file('photo')->storeAs(
                        'users',
                        $filename,
                        'public'
                    );
                    $user->photo = 'storage/' . $path;
                }
                if ($request->password) {
                    $validated = $request->validate([
                        'password' => 'required|min:6',
                    ]);
                    $user->password = Hash::make($request->password);
                }

                $user->save();
            }

            if ($user->is_chercheur or $user->is_doctorant) {
                if ($request->photo) {
                    $validated = $request->validate([
                        'photo' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048'
                    ]);

                    Storage::delete('public'. substr($user->photo, 7));
                    $filename = time() . $user->name . '.' . $request->photo->extension();
                    $path = $request->file('photo')->storeAs(
                        'users',
                        $filename,
                        'public'
                    );
                    $user->userable->photo = 'storage/' . $path;
                }
                if ($request->numeroTelephone) {
                    $validated = $request->validate([
                        'numero_telephone' => 'required|numeric|digits:10',
                    ]);
                    $user->userable->numero_telephone = $request->numeroTelephone;
                }
                if ($request->email) {
                    $validated = $request->validate([
                        'email' => 'required|email:rfc,dns',
                    ]);
                    $user->userable->email = $request->email;
                }
                $user->userable->save();
            }

            DB::commit();

            return Redirect::route('dashboard')
                ->with('message', 'Le compte a été modifié avec succès');
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::route('dashboard')
                ->with('error', 'L\'opération a échoué');
        }
    }


    /**
     * Remove the specified resource from storage.
     *

     */
    public function destroy($id)
    {

    }


    public function destroy_chercheur(Chercheur $chercheur)
    {
        try {
            DB::beginTransaction();
            Storage::delete('public'. substr($chercheur->photo, 7));
            $user = User::where('userable_id', $chercheur->id)
                ->where('userable_type', '\App\Models\Chercheur')
                ->get()[0];

            $nom = $user->name;
            $email = $user->email;
            $user->delete();
            $chercheur->delete();

            DB::commit();

            try {
                Mail::to($email)->send(new DeleteAccountMail($nom));
            } catch (\Exception $e) {}

            return Redirect::route('membres.index')
                ->with('message', 'Le compte Chercheur a été supprimé avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::route('dashboard')
                ->with('error', 'L\'opération a échoué');
        }
    }

    public function destroy_doctorant(Doctorant $doctorant)
    {
        try {
            DB::beginTransaction();

            Storage::delete('public'. substr($doctorant->photo, 7));

            $user = User::where('userable_id', $doctorant->id)
                ->where('userable_type', '\App\Models\Doctorant')
                ->get()[0];

            $nom = $user->name;
            $email = $user->email;
            $user->delete();
            $doctorant->delete();

            DB::commit();

            try {
                Mail::to($email)->send(new DeleteAccountMail($nom));
            } catch (\Exception $e) {}

            return Redirect::route('membres.index')
                ->with('message', 'Le compte Doctorant a été supprimé avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::route('dashboard')
                ->with('error', 'L\'opération a échoué');
        }
    }
}
