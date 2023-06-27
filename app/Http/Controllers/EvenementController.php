<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Inscription;
use App\Models\Projet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class EvenementController extends Controller
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
        $evenements = Evenement::all()->map(function ($evenement) {
            return [
                'id' => $evenement->id,
                'intitule' => $evenement->intitule,
                'detail' => $evenement->detail,
                'dateEvenement' => $evenement->date_evenement,
                'photo' => '/' . $evenement->photo
            ];
        });

        if ($user->is_admin)
            return Inertia::render('Admin/ShowEvenements',
                [
                    'evenements' => $evenements,
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'photo' => '/' . $user->photo
                    ],
                    'first_time' => $first_time,
                ]);

        elseif ($user->is_chercheur)
            return Inertia::render('Chercheur/ShowEvenements',
                [
                    'evenements' => $evenements,
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'photo' => '/' . $user->photo
                    ],
                    'first_time' => $first_time,
                ]);

        elseif ($user->is_doctorant)
            return Inertia::render('Doctorant/ShowEvenements',
                [
                    'evenements' => $evenements,
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'photo' => '/' . $user->photo
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
                'date' => 'required|date',
                'photo' => 'required|image|mimes:jpg,png,jpeg,gif,svg'
            ]);

            $filename = time() . $request->intitule . '.' . $request->photo->extension();
            $path = $request->file('photo')->storeAs(
                'evenements',
                $filename,
                'public'
            );

            Evenement::create(
                [
                    'intitule' => $request->intitule,
                    'detail' => $request->detail,
                    'date_evenement' => $request->date,
                    'photo' => 'storage/' . $path,
                ]
            );
            return Redirect::route('evenements.admin.index')
                ->with('message', 'Nouveau evenement créé avec succès');
        } catch (\Exception $e) {
            return Redirect::route('evenements.admin.index')
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
            $evenement = Evenement::find($request->id);
            if ($request->intitule) {
                $validated = $request->validate([
                    'intitule' => 'required|max:255'
                    ]);
                $evenement->intitule = $request->intitule;
            }
            if ($request->detail) {
                $validated = $request->validate([
                    'detail' => 'required|max:10000',
                    ]);
                $evenement->detail = $request->detail;
            }
            if ($request->date) {
                $validated = $request->validate([
                    'date' => 'required|date'
                ]);
                $evenement->date_evenement = $request->date;
            }
            if ($request->photo)
            {
                $validated = $request->validate([
                    'photo' => 'required|image|mimes:jpg,png,jpeg,gif,svg'
                ]);
                Storage::delete('public'. substr($evenement->photo, 7));
                $filename = time() . $request->title . '.' . $request->photo->extension();
                $path = $request->file('photo')->storeAs(
                    'evenements',
                    $filename,
                    'public'
                );
                $evenement->photo = 'storage/' . $path;
            }
            $evenement->save();
            return Redirect::route('evenements.admin.index')
                ->with('message', 'L\'evenement a été modifié avec succès');
        } catch (\Exception $e) {
            return Redirect::route('evenements.admin.index')
                ->with('error', 'L\'opération a échoué');
        }
    }

    /**
     * Remove the specified resource from storage.

     */
    public function destroy(Evenement $evenement)
    {
        try {
            Storage::delete('public'. substr($evenement->photo, 7));
            $evenement->delete();
            return Redirect::route('evenements.admin.index')
                ->with('message', 'L\'evenement a été supprimé avec succès');
        } catch (\Exception $e) {
            return Redirect::route('evenements.admin.index')
                ->with('error', 'L\'opération a échoué');
        }
    }
}
