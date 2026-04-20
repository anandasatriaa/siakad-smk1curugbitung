<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;
        
        switch ($role) {
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
