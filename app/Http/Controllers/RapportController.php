<?php

namespace App\Http\Controllers;

use App\Models\Rapport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Monolog\Handler\AbstractHandler;
use function GuzzleHttp\Promise\all;
use function Sodium\add;

class RapportController extends Controller
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

            $validated = $request->validate([
                'intitule' => 'required|max:255',
                'detail' => 'required|max:10000',
                'fichier' => 'required|mimetypes:application/pdf'
            ]);

            $filename = time() . $request->intitule . '.' . $request->fichier->extension();
            $path = $request->file('fichier')->storeAs(
                'rapports',
                $filename,
                'public'
            );

            $doctorant_id = Auth::user()->userable->id;
            Rapport::create([
                'intitule' => $request->intitule,
                'detail' => $request->detail,
                'fichier' => 'storage/' . $path,
                'doctorant_id' => $doctorant_id
            ]);

            return Redirect::route('rapports.show')
                ->with('message', 'Nouveau rapport créé avec succès');
        } catch (\Exception $e) {
            return Redirect::route('rapports.show')
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

        if ($user->is_chercheur)
        {
            $rapports = array();
            $all_rapports = array();
            $doctorants = $user->userable->doctorants;
            foreach($doctorants as $doctorant)
            {
                array_push($rapports, $doctorant->rapports);
            }

            for ($i=0; $i < count($rapports); $i++) {
                array_push($all_rapports, $rapports[$i]);
            }

            return Inertia::render('Chercheur/ShowRapport',
                [
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'photo' => '/' . $user->photo
                    ],
                    'first_time' => $first_time,
                    'doctorants' => $doctorants->map(function ($doctorant) {
                        return [
                            'nom' => $doctorant->nom,
                            'prenom' => $doctorant->prenom,
                            'email' => $doctorant->email,
                            'photo' => '/' . $doctorant->photo,
                        ];
                    }),
                    'rapports' => $all_rapports
                ]);
        }

        elseif ($user->is_doctorant)
        {
            $rapports = Auth::user()->userable->rapports;
            return Inertia::render('Doctorant/ShowRapport',
                [
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'photo' => $user->photo
                    ],
                    'first_time' => $first_time,
                    'rapports' => $rapports->map(function ($rapport) {
                        return [
                            'id' => $rapport->id,
                            'intitule' => $rapport->intitule,
                            'detail' => $rapport->detail,
                            'fichier' => $rapport->fichier,
                            'etat' => $rapport->etat

                        ];
                    })
                ]);
        }
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
            $rapport = Rapport::find($request->id);
            if ($request->intitule) {
                $validated = $request->validate([
                    'intitule' => 'required|max:255'
                ]);
                $rapport->intitule = $request->intitule;
            }
            if ($request->detail) {
                $validated = $request->validate([
                    'detail' => 'required|max:10000'
                ]);
                $rapport->detail = $request->detail;
            }
            if ($request->fichier)
            {
                $validated = $request->validate([
                    'fichier' => 'required|mimetypes:application/pdf'
                ]);

                $filename = time() . $request->intitule . '.' . $request->fichier->extension();
                $path = $request->file('fichier')->storeAs(
                    'rapports',
                    $filename,
                    'public'
                );
                $rapport->fichier = 'storage/' . $path;
            }
            $rapport->save();
            return Redirect::route('rapports.show')
                ->with('message', 'Le rapport a été modifié avec succès');
        } catch (\Exception $e) {
            return Redirect::route('brevets.index')
                ->with('error', 'L\'opération a échoué');
        }

    }

    /**
     * Remove the specified resource from storage.
     *

     *
     */
    public function destroy(Rapport $rapport)
    {
        try {
            Storage::delete('public/rapports', $rapport->fichier);
            $rapport->delete();
            return Redirect::route('rapports.show')
                ->with('message', 'Le rapport a été supprimé avec succès');
        } catch (\Exception $e) {
            return Redirect::route('brevets.index')
                ->with('error', 'L\'opération a échoué');
        }
    }

    public function active(Request $request)
    {
        try {
            $rapport = Rapport::find($request->id);
            if (!$rapport->etat)
                $rapport->etat = true;
            else
                $rapport->etat = false;
            $rapport->save();

            $rapport = Rapport::find($request->id);
            $message = "Le rapport a été " . ($rapport->etat ? "accepté" : "rejeté") . " avec succès";

            return Redirect::route('doctorants.rapports.show')
                ->with('message', $message);
        } catch (\Exception $e) {
            return Redirect::route('brevets.index')
                ->with('error', 'L\'opération a échoué');
        }

    }

    public function download(Request $request)
    {
        $rapport = Rapport::find($request->id);
        return Storage::download('public'. substr($rapport->fichier, 7));

    }
}
