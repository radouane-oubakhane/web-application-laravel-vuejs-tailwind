<?php

namespace App\Http\Controllers;

use App\Models\Doctorant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class DoctorantController extends Controller
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

        return Inertia::render('Chercheur/ShowDoctorant',
            [
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo
                ],
                'first_time' => $first_time,
                'doctorants' => Auth::user()->userable->doctorants->map(function ($docrotan) {
                    return [
                        'nom' => $docrotan->nom,
                        'prenom' => $docrotan->prenom,
                        'email' => $docrotan->email,
                        'photo' => $docrotan->photo
                    ];
                }),
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

     */
    public function update(Request $request)
    {
        try {
            $doctorant = Doctorant::find($request->id);
            if ($request->sujet) {
                $validated = $request->validate([
                    'sujet' => 'required|max:255'
                ]);
                $doctorant->sujet = $request->sujet;
            }
            if ($request->encadrant)
                $doctorant->chercheur_id = $request->encadrant;
            $doctorant->save();
            return Redirect::route('membres.index')
                ->with('message', 'Le compte Doctorant a été modifié avec succès');
        } catch (\Exception $e) {
            return Redirect::route('membres.index')
                ->with('error', 'L\'opération a échoué');
        }
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
