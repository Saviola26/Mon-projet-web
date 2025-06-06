<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Seminaire;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $upcomingSeminars = [];
        $upcomingSeminars = Seminaire::whereNotNull('date_validee')
                                     ->where('statut', '!=', 'termine')
                                     ->where('date_validee', '>=', Carbon::today())
                                     ->where('date_validee', '<=', Carbon::today()->addDays(7))
                                     ->orderBy('date_validee')
                                     ->get();

        switch ($user->role) {
            case 'admin':
                return view('dashboard.admin', compact('user', 'upcomingSeminars'));
            case 'presentateur':
                return view('dashboard.presentateur', compact('user', 'upcomingSeminars'));
            case 'etudiant':
                return view('dashboard.etudiant', compact('user', 'upcomingSeminars'));
            default:
                abort(403, 'Rôle inconnu.');
        }
    }
}

