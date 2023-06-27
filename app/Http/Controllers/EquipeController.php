<?php

namespace App\Http\Controllers;

use App\Models\Chercheur;
use App\Models\Equipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use function Symfony\Component\Translation\t;

class EquipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index()
    {
        $user = Auth::user();

        $first_time = (Auth::user()->updated_at == Auth::user()->created_at) ? true : false;

        $equipes = Equipe::all()->map(function ($equipe) {
            if ($equipe->chercheur) $chercheur = $equipe->chercheur->nom;
            else $chercheur = 'Non spécifié';
            return [
                'id' => $equipe->id,
                'nom' => $equipe->nom,
                'detail' => $equipe->detail,
                'chefEquipe' => $chercheur,
            ];
        });
        $chercheurs = Chercheur::all();
        return Inertia::render('Admin/ShowEquipes',
            [
                'chercheurs' => $chercheurs->map(function ($chercheur) {
                    return [
                        'id' => $chercheur->id,
                        'nom' => $chercheur->nom,
                        'prenom' => $chercheur->prenom
                    ];
                }),
                'equipes' => $equipes,
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo
                ],
                'first_time' => $first_time
            ]);
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
     *
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|max:255',
                'detail' => 'required|max:10000'
            ]);

            Equipe::create(
                [
                    'nom' => $request->nom,
                    'detail' => $request->detail,
                    'chercheur_id' => $request->chefEquipe
                ]
            );
            return Redirect::route('equipes.index')
                ->with('message', 'Une nouvelle équipe a été créée avec succès');
        } catch (\Exception $e) {
            return Redirect::route('equipes.index')
                ->with('error', 'L\'opération a échoué');
        }
    }

    /**
     * Display the specified resource.
     *

     */
    public function show()
    {
        $user = Auth::user();

        $first_time = (Auth::user()->updated_at == Auth::user()->created_at) ? true : false;


        if ($team = Auth::user()->userable->equipe)
        {
            if ($team->chercheur) $chercheur = $team->chercheur->id;
            else $chercheur = 'Non spécifié';

            return Inertia::render('Chercheur/ShowEquipe',
                [
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'photo' => $user->photo
                    ],
                    'first_time' => $first_time,
                    'chefEquipe' => $chercheur,
                    'team' => $team->chercheurs->map(function ($chercheur) {
                        return [
                            'id' => $chercheur->id,
                            'nom' => $chercheur->nom,
                            'prenom' => $chercheur->prenom,
                            'email' => $chercheur->email,
                            'photo' => $chercheur->photo
                        ];
                    }),
                    'equipe' => [
                        'nom' => $team->nom,
                        'detail' => $team->detail,
                    ],
                ]);
        }
        else
            return Inertia::render('Chercheur/ShowEquipe',
                [
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'photo' => $user->photo
                    ],
                    'chefEquipe' => null,
                    'team' => null,
                    'equipe' => null
                ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *

     */
    public function update(Request $request)
    {
        try {
            $equipe = Equipe::find($request->id);
            if ($request->nom) {
                $validated = $request->validate([
                    'nom' => 'required|max:255'
                ]);
                $equipe->nom = $request->nom;
            }
            if ($request->detail) {
                $validated = $request->validate([
                    'detail' => 'required|max:10000'
                ]);
                $equipe->detail = $request->detail;
            }
            if ($request->chefEquipe)
                $equipe->chercheur_id = $request->chefEquipe;

            $equipe->save();

            return Redirect::route('equipes.index')
                ->with('message', 'L\'équipe a été modifié avec succès');
        } catch (\Exception $e) {
            return Redirect::route('equipes.index')
            ->with('error', 'L\'opération a échoué');
        }
    }

    /**
     * Remove the specified resource from storage.
     *

     */
    public function destroy(Equipe $equipe)
    {
        try {
            $equipe->delete();
            return Redirect::route('equipes.index')
                ->with('message', 'L\'équipe a été supprimé avec succès');
        } catch (\Exception $e) {
            return Redirect::route('equipes.index')
                ->with('error', 'L\'opération a échoué');
        }
    }
}
