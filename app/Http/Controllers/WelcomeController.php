<?php

namespace App\Http\Controllers;

use App\Models\Chercheur;
use App\Models\Evenement;
use App\Models\LabInfo;
use App\Models\Partenaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Welcome', [
            'labInfo' => LabInfo::all()->map(function ($labinfo) {
                return [
                    'id' => $labinfo->id,
                    'nom' => $labinfo->nom,
                    'detail'=> $labinfo->detail,
                    'email'=> $labinfo->email,
                    'numeroTelephone'=> $labinfo->numero_telephone,
                    'adresse'=> $labinfo->adresse
                ];
            }),
            'evenements' => Evenement::all()->map(function ($evenement) {
                return [
                    'id' => $evenement->id,
                    'intitule' => $evenement->intitule,
                    'detail' => $evenement->detail,
                    'dateEvenement' => $evenement->date_evenement,
                    'photo' => $evenement->photo
                ];
            }),
            'partenaires' => Partenaire::all()->map(function ($partenaire) {
                return [
                    'id' => $partenaire->id,
                    'nom' => $partenaire->nom,
                    'detail' => $partenaire->detail
                ];
            }),
            'chercheurs' => Chercheur::all()->splice(0, 9)->map(function ($chercheur) {
                return [
                    'id' => $chercheur->id,
                    'photo' => $chercheur->photo
                ];
            }),
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register')
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
