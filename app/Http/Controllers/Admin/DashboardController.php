<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdhesionBenevole;
use App\Models\AdhesionCommercant;
use App\Models\User;
use Illuminate\Support\Facades\DB;



class DashboardController extends Controller
{
    // public function index(){
    //     $users = User::all();
    //     $activeCount = User::where('banned', 0)->count();
    //     $bannedCount = User::where('banned', 1)->count();

    //     // dd($activeCount, $bannedCount);
    //     return view('admin.dashboard', compact('users', 'activeCount', 'bannedCount'));
    // }


    public function index()
    {
        $year = now()->year;

        // Données mensuelles pour les inscriptions
        $registrations = User::selectRaw('strftime("%m", created_at) as month, COUNT(*) as count')
                            ->whereYear('created_at', $year)
                            ->groupBy('month')
                            ->pluck('count', 'month');

        // Données mensuelles pour les bannissements
        $bannissements = User::selectRaw('strftime("%m", created_at) as month, COUNT(*) as count')
                            ->where('banned', 1)
                            ->whereYear('created_at', $year)
                            ->groupBy('month')
                            ->pluck('count', 'month');

        // Répartition des utilisateurs par rôle
        $roles = User::selectRaw('role, COUNT(*) as count')
                    ->groupBy('role')
                    ->pluck('count', 'role');

        // Comptage des utilisateurs actifs et inactifs
        $activeUsersCount = User::where('banned', 0)->count();
        $usersTotalCount = User::count();

        $months = ['01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', 
                '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'];

        $registrationData = [];
        $bansData = [];

        foreach ($months as $index => $month) {
            $registrationData[] = $registrations[$index] ?? 0;
            $bansData[] = $bannissements[$index] ?? 0;
        }

        $roleLabels = $roles->keys()->toArray();
        $roleCounts = $roles->values()->toArray();


        // Comptage des candidatures bénévoles par statut
        $benevoleStatusData = DB::table('adhesion_benevoles')
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Comptage des candidatures commerçants par statut
        $commercantStatusData = DB::table('adhesion_commercants')
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('admin.dashboard', compact('registrationData', 'bansData', 'roleLabels', 'roleCounts', 'months', 'activeUsersCount', 'usersTotalCount', 'benevoleStatusData', 
        'commercantStatusData'));
    }




    
    
}
