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
            'nip' => 'nullable|string|max:50|unique:guru',
            'nuptk' => 'nullable|string|max:50|unique:guru',
            'nik' => 'nullable|string|max:20|unique:guru',
            'nama_guru' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string|max:50',
            'status_kepegawaian' => 'nullable|string|max:100',
            'jenis_ptk' => 'nullable|string|max:100',
            'no_telp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Auto create user
        $email = $request->nip . '@smkn1curugbitung.sch.id';

        $user = User::firstOrCreate([
            'email' => $email
        ], [
            'name' => $request->nama_guru,
            'password' => Hash::make('smkn1curugbitung'),
            'role' => 'guru',
        ]);

        $data = [
            'user_id' => $user->id,
            'nip' => $request->nip,
            'nuptk' => $request->nuptk,
            'nik' => $request->nik,
            'nama_guru' => $request->nama_guru,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'agama' => $request->agama,
            'status_kepegawaian' => $request->status_kepegawaian,
            'jenis_ptk' => $request->jenis_ptk,
            'no_telp' => $request->no_telp,
        ];

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('guru', 'public');
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
            'nip' => 'nullable|string|max:50|unique:guru,nip,' . $guru->id,
            'nuptk' => 'nullable|string|max:50|unique:guru,nuptk,' . $guru->id,
            'nik' => 'nullable|string|max:20|unique:guru,nik,' . $guru->id,
            'nama_guru' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string|max:50',
            'status_kepegawaian' => 'nullable|string|max:100',
            'jenis_ptk' => 'nullable|string|max:100',
            'no_telp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only('nip', 'nuptk', 'nik', 'nama_guru', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'agama', 'status_kepegawaian', 'jenis_ptk', 'no_telp');

        if ($request->hasFile('foto')) {
            if ($guru->foto) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($guru->foto);
            }
            $data['foto'] = $request->file('foto')->store('guru', 'public');
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
