<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Guru;
use App\Models\Siswa;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $guru = null;
        $siswa = null;

        if ($user->role === 'guru') {
            $guru = Guru::where('user_id', $user->id)->first();
        } elseif ($user->role === 'siswa') {
            $siswa = Siswa::where('user_id', $user->id)->first();
        }

        return view('profile.index', compact('user', 'guru', 'siswa'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        if ($user->role === 'guru') {
            $guru = Guru::where('user_id', $user->id)->first();
            if ($guru) {
                $guru->nama_guru = $request->name;
                
                if ($request->hasFile('foto')) {
                    if ($guru->foto) {
                        Storage::disk('public')->delete($guru->foto);
                    }
                    $guru->foto = $request->file('foto')->store('guru', 'public');
                }
                $guru->save();
            }
        } elseif ($user->role === 'siswa') {
            $siswa = Siswa::where('user_id', $user->id)->first();
            if ($siswa) {
                $siswa->nama_siswa = $request->name;
                
                if ($request->hasFile('foto')) {
                    if ($siswa->foto) {
                        Storage::disk('public')->delete($siswa->foto);
                    }
                    $siswa->foto = $request->file('foto')->store('siswa', 'public');
                }
                $siswa->save();
            }
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
