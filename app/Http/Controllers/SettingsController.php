<?php

namespace App\Http\Controllers;

use App\Models\LabInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *

     */
    public function index()
    {
        $user = Auth::user();

        $first_time = (Auth::user()->updated_at == Auth::user()->created_at) ? true : false;

        $lab_infos = LabInfo::all()[0];
        return Inertia::render('Admin/ShowSettings',
            [
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo
                ],
                'first_time' => $first_time,
                'lab' => [
                    'nom' => $lab_infos->nom,
                    'detail' => $lab_infos->detail,
                    'email' => $lab_infos->email,
                    'numeroTelephone' => $lab_infos->numero_telephone,
                    'adresse' => $lab_infos->adresse
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
            $lab_infos = LabInfo::all()[0];
            if ($request->nom) {
                $validated = $request->validate([
                    'nom' => 'required|max:255'
                ]);
                $lab_infos->nom = $request->nom;
            }
            if ($request->email){
                $validated = $request->validate([
                    'email' => 'required|email:rfc,dns',
                ]);
                $lab_infos->email = $request->email;
            }
            if ($request->numeroTelephone) {
                $validated = $request->validate([
                    'numeroTelephone' => 'required|numeric|digits:10'
                ]);
                $lab_infos->numero_telephone = $request->numeroTelephone;
            }
            if ($request->adresse) {
                $validated = $request->validate([
                    'adresse' => 'required|max:255'
                ]);
                $lab_infos->adresse = $request->adresse;
            }
            if ($request->detail) {
                $validated = $request->validate([
                    'detail' => 'required|max:10000'
                ]);
                $lab_infos->detail = $request->detail;
            }

            $lab_infos->save();
            return Redirect::route('settings.index')
                ->with('message', 'Les paramètres ont été modifiés avec succès');
        } catch (\Exception $e) {
        return Redirect::route('settings.index')
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
