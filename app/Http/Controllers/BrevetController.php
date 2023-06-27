<?php

namespace App\Http\Controllers;

use App\Models\Brevet;
use App\Models\Chercheur;
use App\Models\Rapport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use PHPUnit\Exception;
use function Symfony\Component\String\b;

class BrevetController extends Controller
{
    /**
     * Display a listing of the resource.
     *

     */
    public function index()
    {
        $user = Auth::user();

        $first_time = (Auth::user()->updated_at == Auth::user()->created_at) ? true : false;

        $brevets = array();
        $all_brevets = array();
        $chercheurs = Chercheur::all();
        foreach($chercheurs as $chercheur)
        {
            array_push($brevets, $chercheur->brevets);
        }

        for ($i=0; $i < count($brevets); $i++) {
            array_push($all_brevets, $brevets[$i]);
        }

        return Inertia::render('Admin/ShowBrevet',
            [
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo
                ],
                'first_time' => $first_time,
                'chercheurs' => $chercheurs->map(function ($chercheur) {
                    return [
                        'nom' => $chercheur->nom,
                        'prenom' => $chercheur->prenom,
                        'email' => $chercheur->email,
                        'photo' => $chercheur->photo
                    ];
                }),
                'brevets' => $all_brevets
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
                'intitule' => 'required|max:255',
                'detail' => 'required|max:10000',
                'type' => 'required|max:255',
                'fichier' => 'required|mimetypes:application/pdf'
            ]);

            $filename = time() . $request->intitule . '.' . $request->fichier->extension();
            $path = $request->file('fichier')->storeAs(
                'brevets',
                $filename,
                'public'
            );

            $chercheur_id = Auth::user()->userable->id;
            Brevet::create([
                'intitule' => $request->intitule,
                'detail' => $request->detail,
                'type' => $request->type,
                'fichier' => 'storage/' . $path,
                'chercheur_id' => $chercheur_id
            ]);

            return Redirect::route('brevets.show')
                ->with('message', 'Nouveau brevet créé avec succès');
        } catch (\Exception $e) {
            return Redirect::route('brevets.show')
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
        $brevets = Auth::user()->userable->brevets;
        $user = Auth::user();

        $first_time = (Auth::user()->updated_at == Auth::user()->created_at) ? true : false;

        return Inertia::render('Chercheur/ShowBrevet',
            [
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'photo' => '/' . $user->photo
                ],
                'first_time' => $first_time,
                'brevets' => $brevets->map(function ($brevet) {
                    return [
                        'id' => $brevet->id,
                        'intitule' => $brevet->intitule,
                        'detail' => $brevet->detail,
                        'type' => $brevet->type,
                        'fichier' => $brevet->fichier,
                        'etat' => $brevet->etat

                    ];
                })
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
            DB::beginTransaction();
            $brevet = Brevet::find($request->id);
            if ($request->intitule) {
                $validated = $request->validate([
                    'intitule' => 'required|max:255',
                ]);
                $brevet->intitule = $request->intitule;
            }
            if ($request->detail) {
                $validated = $request->validate([
                    'detail' => 'required|max:10000',
                ]);
                $brevet->detail = $request->detail;
            }
            if ($request->type) {
                $validated = $request->validate([
                    'type' => 'required|max:255',
                ]);
                $brevet->type = $request->type;
            }
            if ($request->fichier)
            {
                $validated = $request->validate([
                    'fichier' => 'required|mimetypes:application/pdf'
                ]);
                Storage::delete('public'. substr($brevet->fichier, 7));
                $brevet->delete();
                $filename = time() . $request->intitule . '.' . $request->fichier->extension();
                $path = $request->file('fichier')->storeAs(
                    'brevets',
                    $filename,
                    'public'
                );
                $brevet->fichier = 'storage/' . $path;
            }
            $brevet->save();
            DB::commit();
            return Redirect::route('brevets.show')
                ->with('message', 'Le brevet a été modifié avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::route('brevets.show')
                ->with('error', 'L\'opération a échoué');
        }
    }

    /**
     * Remove the specified resource from storage.
     *

     */
    public function destroy(Brevet $brevet)
    {
        try {
            Storage::delete('public'. substr($brevet->fichier, 7));
            $brevet->delete();
            return Redirect::route('brevets.show')
                ->with('message', 'Le brevet a été supprimé avec succès');
        } catch (\Exception $e) {
            return Redirect::route('brevets.show')
                ->with('error', 'L\'opération a échoué');
        }
    }

    public function active(Request $request)
    {
        try {
            DB::beginTransaction();
            $brevet = Brevet::find($request->id);
            if (!$brevet->etat)
                $brevet->etat = true;
            else
                $brevet->etat = false;
            $brevet->save();

            $brevet = Brevet::find($request->id);
            DB::commit();

            $message = "Le brevet a été " . ($brevet->etat ? "accepté" : "rejeté") . " avec succès";
            return Redirect::route('brevets.index')
                ->with('message', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::route('brevets.index')
                ->with('error', 'L\'opération a échoué');
        }

    }

    public function download(Request $request)
    {
        $brevet = Brevet::find($request->id);
        return Storage::download('public'. substr($brevet->fichier, 7));

    }
}

