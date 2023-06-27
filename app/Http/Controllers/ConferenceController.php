<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use App\Models\Evenement;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use function GuzzleHttp\Promise\all;
use function Symfony\Component\String\u;

class ConferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
        $user_id = Auth::user()->userable->id;
        $user = Auth::user();
        try {
            $validated = $request->validate([
                'intitule' => 'required|max:255',
                'detail' => 'required|max:10000',
                'date' => 'required|date',
                'ville' => 'required|max:255'
            ]);
            if ($request->type === 1)
            {
                Conference::create(
                    [
                        'intitule' => $request->intitule,
                        'detail' => $request->detail,
                        'date' => $request->date,
                        'ville' => $request->ville,
                        'conferenceable_id' => $user_id,
                        'conferenceable_type' => '\App\Models\Doctorant'
                    ]
                );
                return Redirect::route('conferences.doctorant.show')
                    ->with('message', 'Nouvelle Conference créée avec succès');
            }
            elseif ($request->type === 2)
            {
                Conference::create(
                    [
                        'intitule' => $request->intitule,
                        'detail' => $request->detail,
                        'date' => $request->date,
                        'ville' => $request->ville,
                        'conferenceable_id' => $user_id,
                        'conferenceable_type' => '\App\Models\Chercheur'
                    ]
                );
                return Redirect::route('conferences.chercheur.show')
                    ->with('message', 'Nouvelle Conference créée avec succès');
            }
        }
        catch (\Exception $e) {
            if ($user->is_chercheur)
                return Redirect::route('conferences.chercheur.show')
                    ->with('error', 'L\'opération a échoué');
            elseif ($user->is_doctorant)
                return Redirect::route('conferences.doctorant.show')
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
        $all_conferences = Conference::all();

        $first_time = (Auth::user()->updated_at == Auth::user()->created_at) ? true : false;

        $user = Auth::user();
        if ($user->is_chercheur)
        {

            $conferences = $all_conferences->where('conferenceable_id', '=', $user->userable->id)
                                            ->where('conferenceable_type', '=', '\App\Models\Chercheur');
            return Inertia::render('Chercheur/ShowConference',
                [
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'photo' => '/' . $user->photo
                    ],
                    'first_time' => $first_time,
                    'conferences' => $conferences->map(function ($conference) {
                        return [
                            'id' => $conference->id,
                            'intitule' => $conference->intitule,
                            'detail' => $conference->detail,
                            'date' => $conference->date,
                            'ville' => $conference->ville
                        ];
                    })
                ]);
        }
        elseif ($user->is_doctorant)
        {

            $conferences = $all_conferences->where('conferenceable_id', '=', $user->userable->id)
                                           ->where('conferenceable_type', '=', '\App\Models\Doctorant');

            return Inertia::render('Doctorant/ShowConference',
                [
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'photo' => '/' . $user->photo
                    ],
                    'first_time' => $first_time,
                    'conferences' => $conferences->map(function ($conference) {
                        return [
                            'id' => $conference->id,
                            'intitule' => $conference->intitule,
                            'detail' => $conference->detail,
                            'date' => $conference->date,
                            'ville' => $conference->ville
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
        //
    }

    /**
     * Update the specified resource in storage.
     *

     */
    public function update(Request $request)
    {
        try {
            $user = Auth::user();
            $conference = Conference::find($request->id);
            if ($request->intitule) {
                $validated = $request->validate([
                    'intitule' => 'required|max:255'
                ]);
                $conference->intitule = $request->intitule;
            }
            if ($request->detail) {
                $validated = $request->validate([
                    'detail' => 'required|max:10000'
                ]);
                $conference->detail = $request->detail;
            }
            if ($request->date) {
                $validated = $request->validate([
                    'date' => 'required|date'
                ]);
                $conference->date = $request->date;
            }
            if ($request->ville) {
                $validated = $request->validate([
                    'ville' => 'required|max:255'
                ]);
                $conference->ville = $request->ville;
            }

            $conference->save();

            if ($user->is_chercheur)
                return Redirect::route('conferences.chercheur.show')
                    ->with('message', 'La conference a été modifié avec succès');

            elseif ($user->is_doctorant)
                return Redirect::route('conferences.doctorant.show')
                    ->with('message', 'La conference a été modifié avec succès');
        } catch (\Exception $e) {
            if ($user->is_chercheur)
                return Redirect::route('conferences.chercheur.show')
                    ->with('error', 'L\'opération a échoué');
            elseif ($user->is_doctorant)
                return Redirect::route('conferences.doctorant.show')
                    ->with('error', 'L\'opération a échoué');
        }
    }

    /**
     * Remove the specified resource from storage.
     *

     */
    public function destroy(Conference $conference)
    {
        try {
            $user = Auth::user();
            $conference->delete();
            if ($user->is_chercheur)
                return Redirect::route('conferences.chercheur.show');
            elseif ($user->is_doctorant)
                return Redirect::route('conferences.doctorant.show');
        } catch (\Exception $e) {
            if ($user->is_chercheur)
                return Redirect::route('conferences.chercheur.show')
                    ->with('error', 'L\'opération a échoué');
            elseif ($user->is_doctorant)
                return Redirect::route('conferences.doctorant.show')
                    ->with('error', 'L\'opération a échoué');

        }
    }
}
