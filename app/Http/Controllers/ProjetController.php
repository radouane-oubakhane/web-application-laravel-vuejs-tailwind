<?php

namespace App\Http\Controllers;

use App\Models\Equipe;
use App\Models\Evenement;
use App\Models\Projet;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProjetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index()
    {

        $projets = Projet::all()->map(function ($projet) {
            if ($projet->equipe)
                $equipe = $projet->equipe->nom;
            else $equipe = 'Non spécifié';
            return [
                'id' => $projet->id,
                'intitule' => $projet->intitule,
                'detail' => $projet->detail,
                'equipe' => $equipe,
                'photo' => $projet->photo,
                'theme' => $projet->theme->intitule
            ];
        });

        $equipes = Equipe::all()->map(function ($equipe) {
            return [
                'id' => $equipe->id,
                'nom' => $equipe->nom,
            ];
        });

        $themes = Theme::all()->map(function ($theme) {
            return [
                'id' => $theme->id,
                'intitule' => $theme->intitule,

            ];
        });

        $user = Auth::user();

        $first_time = (Auth::user()->updated_at == Auth::user()->created_at) ? true : false;

        return Inertia::render('Admin/ShowProjets',
            [
                'projets' => $projets,
                'equipes' => $equipes,
                'themes' => $themes,
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo
                ],
                'first_time' => $first_time,
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

     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'intitule' => 'required|max:255',
                'detail' => 'required|max:10000',
                'photo' => 'required|image|mimes:jpg,png,jpeg,gif,svg'
            ]);

            $filename = time() . $request->intitule . '.' . $request->photo->extension();
            $path = $request->file('photo')->storeAs(
                'projets',
                $filename,
                'public'
            );

            Projet::create(
                [
                    'intitule' => $request->intitule,
                    'detail' => $request->detail,
                    'equipe_id' => $request->equipe,
                    'photo' => 'storage/' . $path,
                    'theme_id' => $request->theme
                ]
            );
            return Redirect::route('projets.index')
                ->with('message', 'Nouveau projet créé avec succès');
        } catch (\Exception $e) {
            return Redirect::route('projets.index')
                ->with('error', 'L\'opération a échoué');
        }
    }

    /**
     * Display the specified resource.
     *
     *
     */
    public function show()
    {
        $user = Auth::user();

        $first_time = (Auth::user()->updated_at == Auth::user()->created_at) ? true : false;

        if ($user->userable->equipe)
        {
            $equipe = $user->userable->equipe;
            if ($equipe->projets) {
                $projets = $equipe->projets;
                $chef_equipe = $equipe->chercheur;

                $chefEquipe = [
                    'nom' => $chef_equipe ? $chef_equipe->nom : '',
                    'prenom' => $chef_equipe ? $chef_equipe->prenom : '',
                    'email' => $chef_equipe ? $chef_equipe->email : '',
                    'photo' => $chef_equipe ? $chef_equipe->photo : ''
                ];

                return Inertia::render('Chercheur/ShowProjet',
                    [
                        'user' => [
                            'name' => $user->name,
                            'email' => $user->email,
                            'photo' => $user->photo
                        ],
                        'first_time' => $first_time,
                        'equipe' => [
                            'nom' => $equipe->nom,
                            'detail' => $equipe->detail,
                        ],
                        'chefEquipe' => $chefEquipe,
                        'projets' => $projets->map(function ($projet) {
                            return
                                [
                                    'intitule' => $projet->intitule,
                                    'detail' => $projet->detail,
                                    'photo' => $projet->photo,
                                    'equipe' => $projet->equipe->nom,
                                    'theme' => $projet->theme->intitule,
                                ];
                        })
                    ]);
            }

        }
        else
            return Inertia::render('Chercheur/ShowProjet',
                [
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'photo' => $user->photo
                    ],
                    'first_time' => $first_time,
                    'equipe' => [
                        'nom' => null,
                        'detail' => null,
                    ],
                    'chefEquipe' => [
                        'nom' => null,
                        'prenom' => null,
                        'email' => null,
                        'photo' => null
                    ],
                    'projets' => [
                                'intitule' => null,
                                'detail' => null,
                                'photo' => null,
                                'equipe' => null,
                                'theme' => null,
                            ]
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
        //
    }

    /**
     * Update the specified resource in storage.
     *

     */
    public function update(Request $request)
    {
        try {
            $projet = Projet::find($request->id);
            if ($request->intitule) {
                $validated = $request->validate([
                    'intitule' => 'required|max:255'
                ]);
                $projet->intitule = $request->intitule;
            }
            if ($request->detail) {
                $validated = $request->validate([
                    'detail' => 'required|max:10000'
                ]);
                $projet->detail = $request->detail;
            }
            if ($request->equipe)
                $projet->equipe_id = $request->equipe;
            if ($request->theme)
                $projet->theme_id = $request->theme;
            if ($request->photo)
            {
                $validated = $request->validate([
                    'photo' => 'required|image|mimes:jpg,png,jpeg,gif,svg'
                ]);

                Storage::delete('public'. substr($projet->photo, 7));

                $filename = time() . $request->intitule . '.' . $request->photo->extension();
                $path = $request->file('photo')->storeAs(
                    'projets',
                    $filename,
                    'public'
                );
                $projet->photo = 'storage/' . $path;
            }
            $projet->save();
            return Redirect::route('projets.index')
                ->with('message', 'Le projet a été modifié avec succès');
        } catch (\Exception $e) {
            return Redirect::route('projets.index')
                ->with('error', 'L\'opération a échoué');
        }
    }

    /**
     * Remove the specified resource from storage.
     *

     */
    public function destroy(Projet $projet)
    {
        try {

            Storage::delete('public'. substr($projet->photo, 7));
            $projet->delete();
            return Redirect::route('projets.index')
                ->with('message', 'Le projet a été supprimé avec succès');
        } catch (\Exception $e) {
            return Redirect::route('projets.index')
                ->with('error', 'L\'opération a échoué');
        }
    }
}

