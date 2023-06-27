<?php

namespace App\Http\Controllers;

use App\Models\Brevet;
use App\Models\Chercheur;
use App\Models\Conference;
use App\Models\Doctorant;
use App\Models\Equipe;
use App\Models\Evenement;
use App\Models\Inscription;
use App\Models\Projet;
use App\Models\Rapport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PhpParser\Comment\Doc;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $first_time = (Auth::user()->updated_at == Auth::user()->created_at) ? true : false;

        if ($user->is_admin)
        {
            $admin_widgets = [
                [
                    'title' => 'Membres de laboratoire',
                    'num' => Chercheur::all()->count() + Doctorant::all()->count(),
                    'icon' => 'fa-solid fa-user',
                    'color' => 'text-lab-red'
                ],
                [
                    'title' => 'Nombre de projets',
                    'num' => Projet::all()->count(),
                    'icon' => 'fa-solid fa-list-check',
                    'color' => 'text-lab-blue'
                ],
                [
                    'title' => 'Nombre d\'equipes',
                    'num' => Equipe::all()->count(),
                    'icon' => 'fa-solid fa-people-group',
                    'color' => 'text-lab-purple'
                ]
            ];

            $inscriptions = Inscription::all()->map(function ($inscription) {
                return [
                    'id' => $inscription->id,
                    'nom' => $inscription->nom,
                    'prenom' => $inscription->prenom,
                    'email' => $inscription->email,
                    'numero_telephone' => $inscription->numero_telephone,
                    'cin' => $inscription->cin,
                    'cne' => $inscription->cne,
                    'fonction' => $inscription->fonction->intitule,
                    'fonction_id' => $inscription->fonction_id,
                    'photo' => $inscription->photo
                ];
            });

            return Inertia::render('Dashboards/AdminDashboard',
                [
                    'widgets' => $admin_widgets,
                    'inscriptions' => $inscriptions,
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'photo' => $user->photo
                    ],
                    'first_time' => $first_time,
                    'chercheurs' => Chercheur::all()->map(function ($chercheur) {
                        return [
                                'id' => $chercheur->id,
                                'nom' => $chercheur->nom
                        ];
                    }),
                    'equipes' => Equipe::all()->map(function ($equipe) {
                        return [
                          'id' => $equipe->id,
                          'nom' => $equipe->nom
                        ];
                    })
                    ]);
        }

        elseif ($user->is_chercheur)
        {
            $chercheur_widgets = [
                [
                    'title' => 'Doctorants Encadrés',
                    'num' => Auth::user()->userable->doctorants->count(),
                    'icon' => 'fa-solid fa-user',
                    'color' => 'text-lab-red'
                ],
                [
                    'title' => 'Nombre de conference',
                    'num' => DB::table('conferences')
                    ->where('conferenceable_type', '=', '\App\Models\Chercheur')
                    ->where('conferenceable_id', '=', Auth::user()->userable->id)
                    ->count(),
                    'icon' => 'fa-solid fa-list-check',
                    'color' => 'text-lab-blue'
                ],
                [
                    'title' => 'Nombre de brevets',
                    'num' => Auth::user()->userable->brevets->count(),
                    'icon' => 'fa-solid fa-people-group',
                    'color' => 'text-lab-purple'
                ]
            ];

            $chercheur_table = Auth::user()->userable->brevets
                ->map(function ($brevet) {
                    return [
                        'id' => $brevet->id,
                        'intitule' => $brevet->intitule,
                        'detail' => $brevet->detail,
                        'type' => $brevet->type,
                        'fichier' => $brevet->fichier,
                        'etat' => $brevet->etat,
                    ];
                });
            return Inertia::render('Dashboards/ChercheurDashboard',
                [
                    'widgets' => $chercheur_widgets,
                    'brevets' => $chercheur_table,
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'photo' => $user->photo
                    ],
                    'first_time' => $first_time,
                ]);
        }


        elseif ($user->is_doctorant)
        {

            $doctorant_widgets = [
                [
                    'title' => 'Nombre de rapports',
                    'num' => DB::table('rapports')
                        ->where('doctorant_id', '=',Auth::user()->userable->id)
                        ->count(),
                    'icon' => 'fa-solid fa-user',
                    'color' => 'text-lab-red'
                ],
                [
                    'title' => 'Nombre de conference',
                    'num' => DB::table('conferences')
                        ->where('conferenceable_type', '=', '\App\Models\Doctorant')
                        ->where('conferenceable_id', '=', Auth::user()->userable->id)
                        ->count(),
                    'icon' => 'fa-solid fa-list-check',
                    'color' => 'text-lab-blue'
                ]
            ];

            $doctorant = Auth::user()->userable;
            $doctorant_table = $doctorant->rapports
                ->map(function ($rapport) {
                    return [
                        'id' => $rapport->id,
                        'intitule' => $rapport->intitule,
                        'detail' => $rapport->detail,
                        'fichier' => $rapport->fichier,
                        'etat' => $rapport->etat,
                    ];
                });
            $user = Auth::user();
            return Inertia::render('Dashboards/DoctorantDashboard',
                [
                    'widgets' => $doctorant_widgets,
                    'rapports' => $doctorant_table,
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'photo' => $user->photo
                    ],
                    'first_time' => $first_time,
                ]);
        }

        return Inertia::render('Exceptions/AccountDisabled');
    }
}
