<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        switch ($user->role) {
            case 'superadmin':
            case 'admin':
                return view('dashboard.admin');
            case 'guru':
                return view('dashboard.guru');
            case 'siswa':
                return view('dashboard.siswa');
            default:
                return view('dashboard.default');
        }
    }
}
