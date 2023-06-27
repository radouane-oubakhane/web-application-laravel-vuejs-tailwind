<?php

namespace App\Http\Controllers;

use App\Mail\PassMail;
use App\Models\Equipe;
use App\Models\Fonction;
use App\Models\Inscription;
use App\Models\Chercheur;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use PHPUnit\Exception;

class InscriptionController extends Controller
{
    /**
     * Display a listing of the resource.

     */
    public function index(): \Inertia\Response
    {
        $user = Auth::user();

        $first_time = (Auth::user()->updated_at == Auth::user()->created_at) ? true : false;

        $inscriptions = Inscription::all()->map(function ($inscription) {
            return [
                'id' => $inscription->id,
                'nom' => $inscription->nom,
                'prenom' => $inscription->prenom,
                'email' => $inscription->email,
                'numero_telephone' => $inscription->numero_telephone,
                'cin' => $inscription->cin,
                'cne' => $inscription->cne,
                'fonction' => $inscription->fonction->intitule,
                'fonction_id' => $inscription->fonction_id,
                'photo' => $inscription->photo
            ];
        });
        return Inertia::render('Admin/ShowInscription',
            [
                'inscriptions' => $inscriptions,
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo
                ],
                'first_time' => $first_time,
                'chercheurs' => Chercheur::all()->map(function ($chercheur) {
                    return [
                        'id' => $chercheur->id,
                        'nom' => $chercheur->nom
                    ];
                }),
                'equipes' => Equipe::all()->map(function ($equipe) {
                    return [
                        'id' => $equipe->id,
                        'nom' => $equipe->nom
                    ];
                })
                ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Inscription/Register', [
            'fonctions' => Fonction::all()->map(function ($fonction) {
                return [
                    'id' => $fonction->id,
                    'intitule' => $fonction->intitule
                ];
            })
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     *
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|max:255',
                'prenom' => 'required|max:255',
                'email' => 'required|email:rfc,dns',
                'numeroTelephone' => 'required|numeric|digits:10',
                'dateNaissance' => 'required|date',
                'lieuxNaissance' => 'required|max:255',
                'cin' => 'required|max:255',
                'photo' => 'required|image|mimes:jpg,png,jpeg,gif,svg'
            ]);


            if ($request->fonctionId == 1) {
                if (!trim($request->cne))
                    throw new \Exception();
                else
                $validated = $request->validate([
                    'cne' => 'required|max:255'
                ]);
            }


            $filename = time() . $request->cin . '.' . $request->photo->extension();
            $path = $request->file('photo')->storeAs(
                'users',
                $filename,
                'public'
            );
            Inscription::create([
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                    'email' => $request->email,
                    'numero_telephone' => $request->numeroTelephone,
                    'cin' => $request->cin,
                    'cne' => $request->cne,
                    'photo' => 'storage/' . $path,
                    'date_naissance' => $request->dateNaissance,
                    'lieux_naissance' => $request->lieuxNaissance,
                    'fonction_id' => $request->fonctionId
                ]
            );
            return Redirect::route('successfulregistration');
        } catch (\Exception $e) {
            return Redirect::route('failedregistration');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *

     */
    public function destroy(Inscription $inscription)
    {
        try {
            Storage::delete('public/users', $inscription->photo);
            $inscription->delete();
            try {
                Mail::to($inscription->email)->send(new PassMail($inscription, null, false));
            } catch (\Exception $e) {}
            return Redirect::route('inscriptions.index')
                ->with('message', 'La demande d\'inscriptiona a été supprimé avec succès');
        } catch (\Exception $e) {
            return Redirect::route('inscriptions.index')
                ->with('error', 'L\'opération a échoué');
        }
    }
}
