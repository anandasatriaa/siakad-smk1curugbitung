<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelases = Kelas::with('wali_kelas')->latest()->get();
        return view('admin.kelas.index', compact('kelases'));
    }

    public function create()
    {
        $gurus = Guru::all();
        return view('admin.kelas.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:100|unique:kelas',
            'wali_kelas_id' => 'nullable|exists:gurus,id',
        ]);

        Kelas::create($request->only('nama_kelas', 'wali_kelas_id'));

        return redirect()->route('admin.kelas.index')->with('success', 'Data Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kela)
    {
        // the scaffold generates variable $kela because of singular table name trickiness, but let's stick to $kela or change it
        $kelas = $kela;
        $gurus = Guru::all();
        return view('admin.kelas.edit', compact('kelas', 'gurus'));
    }

    public function update(Request $request, Kelas $kela)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:100|unique:kelas,nama_kelas,' . $kela->id,
            'wali_kelas_id' => 'nullable|exists:gurus,id',
        ]);

        $kela->update($request->only('nama_kelas', 'wali_kelas_id'));

        return redirect()->route('admin.kelas.index')->with('success', 'Data Kelas berhasil diubah.');
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();
        return redirect()->route('admin.kelas.index')->with('success', 'Data Kelas berhasil dihapus.');
    }
}
