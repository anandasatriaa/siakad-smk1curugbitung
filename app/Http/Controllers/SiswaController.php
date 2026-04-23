<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::with(['user', 'kelas'])->latest()->get();
        return view('admin.siswa.index', compact('siswas'));
    }

    public function create()
    {
        $kelases = Kelas::all();
        return view('admin.siswa.create', compact('kelases'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|string|max:50|unique:siswas',
            'nama_siswa' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'jabatan' => 'nullable|string|max:100',
        ]);

        $email = strtolower(str_replace(' ', '', $request->nis)) . '@smkn1curugbitung.sch.id';

        $user = User::firstOrCreate([
            'email' => $email
        ], [
            'name' => $request->nama_siswa,
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);

        Siswa::create([
            'user_id' => $user->id,
            'nis' => $request->nis,
            'nama_siswa' => $request->nama_siswa,
            'kelas_id' => $request->kelas_id,
            'jabatan' => $request->jabatan,
        ]);

        return redirect()->route('admin.siswa.index')->with('success', 'Data Siswa dan Akun Login berhasil ditambahkan.');
    }

    public function edit(Siswa $siswa)
    {
        $kelases = Kelas::all();
        return view('admin.siswa.edit', compact('siswa', 'kelases'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nis' => 'required|string|max:50|unique:siswas,nis,' . $siswa->id,
            'nama_siswa' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'jabatan' => 'nullable|string|max:100',
        ]);

        $siswa->update($request->only('nis', 'nama_siswa', 'kelas_id', 'jabatan'));

        if ($siswa->user) {
            $siswa->user->update([
                'name' => $request->nama_siswa,
            ]);
        }

        return redirect()->route('admin.siswa.index')->with('success', 'Data Siswa berhasil diubah.');
    }

    public function destroy(Siswa $siswa)
    {
        if ($siswa->user) {
            $siswa->user->delete();
        }
        $siswa->delete();
        return redirect()->route('admin.siswa.index')->with('success', 'Data Siswa dan Akun Login berhasil dihapus.');
    }
}
