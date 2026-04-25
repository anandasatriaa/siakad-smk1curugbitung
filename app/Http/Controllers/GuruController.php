<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::with('user')->latest()->get();
        return view('admin.guru.index', compact('gurus'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|string|max:50|unique:gurus',
            'nama_guru' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Auto create user
        $email = $request->nip . '@smkn1curugbitung.sch.id';

        $user = User::firstOrCreate([
            'email' => $email
        ], [
            'name' => $request->nama_guru,
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);

        $data = [
            'user_id' => $user->id,
            'nip' => $request->nip,
            'nama_guru' => $request->nama_guru,
            'no_telp' => $request->no_telp,
        ];

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('gurus', 'public');
        }

        Guru::create($data);

        return redirect()->route('admin.guru.index')->with('success', 'Data Guru dan Akun Login berhasil ditambahkan.');
    }

    public function edit(Guru $guru)
    {
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nip' => 'required|string|max:50|unique:gurus,nip,' . $guru->id,
            'nama_guru' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only('nip', 'nama_guru', 'no_telp');

        if ($request->hasFile('foto')) {
            if ($guru->foto) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($guru->foto);
            }
            $data['foto'] = $request->file('foto')->store('gurus', 'public');
        }

        $guru->update($data);

        if ($guru->user) {
            $guru->user->update([
                'name' => $request->nama_guru,
                'email' => $request->nip . '@smkn1curugbitung.sch.id',
            ]);
        }

        return redirect()->route('admin.guru.index')->with('success', 'Data Guru berhasil diubah.');
    }

    public function destroy(Guru $guru)
    {
        if ($guru->user) {
            $guru->user->delete();
        }
        $guru->delete();
        return redirect()->route('admin.guru.index')->with('success', 'Data Guru dan Akun Login berhasil dihapus.');
    }
}
