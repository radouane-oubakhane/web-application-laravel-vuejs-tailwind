<?php

namespace App\Http\Controllers;

use App\Models\Partenaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use PHPUnit\Exception;

class PartenaireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $user = Auth::user();

        $first_time = (Auth::user()->updated_at == Auth::user()->created_at) ? true : false;

        $partenaires = Partenaire::all()->map(function ($partenaire) {
            return [
                'id' => $partenaire->id,
                'nom' => $partenaire->nom,
                'detail' => $partenaire->detail
            ];
        });

        return Inertia::render('Admin/ShowPartenaires',
        [
            'partenaires' => $partenaires,
            'first_time' => $first_time,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'photo' => $user->photo
            ]
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
                'nom' => 'required|max:255',
                'detail' => 'required|max:10000'
            ]);

            Partenaire::create(
                [
                    'nom' => $request->nom,
                    'detail' => $request->detail
                ]
            );
            return Redirect::route('partenaires.index')
                ->with('message', 'Nouveau partenaire créé avec succès');
        } catch (\Exception $e) {
            return Redirect::route('partenaires.index')
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
            $parertenaire = Partenaire::find($request->id);
            if ($request->nom) {
                $validated = $request->validate([
                    'nom' => 'required|max:255'
                ]);
                $parertenaire->nom = $request->nom;
            }
            if ($request->detail) {
                $validated = $request->validate([
                    'detail' => 'required|max:10000'
                ]);
                $parertenaire->detail = $request->detail;
            }

            $parertenaire->save();

            return Redirect::route('partenaires.index')
                ->with('message', 'Le partenaire a été modifié avec succès');
        } catch (\Exception $e) {
            return Redirect::route('partenaires.index')
                ->with('error', 'L\'opération a échoué');
        }

    }

    /**
     * Remove the specified resource from storage.
     *

     */
    public function destroy(Partenaire $partenaire)
    {
        try {
            $partenaire->delete();
            return Redirect::route('partenaires.index')
                ->with('message', 'Le partenaire a été supprimé avec succès');
        } catch (\Exception $e) {
            return Redirect::route('partenaires.index')
                ->with('error', 'L\'opération a échoué');
        }
    }
}
