<?php

namespace App\Http\Controllers;

use App\Models\Chercheur;
use App\Models\Doctorant;
use App\Models\Equipe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use PhpParser\Comment\Doc;
use function Symfony\Component\String\u;

class MembresController extends Controller
{
    /**
     * Display a listing of the resource.
     *

     */
    public function index()
    {
        $chercheurs = Chercheur::all()->map(function ($chercheur) {
            return [
                'id' => $chercheur->id,
                'etat' => User::where('userable_id', Chercheur::find($chercheur->id)->id)
                    ->where('userable_type', '\App\Models\Chercheur')
                    ->get()[0]->is_chercheur,
                'nom' => $chercheur->nom,
                'prenom' => $chercheur->prenom,
                'email' => $chercheur->email,
                'numero_telephone' => $chercheur->numero_telephone,
                'cin' => $chercheur->cin,
                'photo' => $chercheur->photo
            ];
        });
        $doctorants = Doctorant::all()->map(function ($doctorant) {
            return [
                'id' => $doctorant->id,
                'etat' => User::where('userable_id', Doctorant::find($doctorant->id)->id)
                    ->where('userable_type', '\App\Models\Doctorant')
                    ->get()[0]->is_doctorant,
                'nom' => $doctorant->nom,
                'prenom' => $doctorant->prenom,
                'email' => $doctorant->email,
                'numero_telephone' => $doctorant->numero_telephone,
                'cin' => $doctorant->cin,
                'photo' => $doctorant->photo
            ];
        });
        $user = Auth::user();

        $first_time = (Auth::user()->updated_at == Auth::user()->created_at) ? true : false;

        return Inertia::render('Admin/ShowMembres',
            [
                'chercheurs' => $chercheurs,
                'doctorants' => $doctorants,
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo
                ],
                'first_time' => $first_time,
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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
