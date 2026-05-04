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
        $assignedGuruIds = Kelas::whereNotNull('wali_kelas_id')->pluck('wali_kelas_id')->toArray();
        $gurus = Guru::whereNotIn('id', $assignedGuruIds)->get();
        return view('admin.kelas.create', compact('gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:100|unique:kelas',
            'wali_kelas_id' => 'nullable|exists:guru,id|unique:kelas,wali_kelas_id',
        ], [
            'wali_kelas_id.unique' => 'Guru tersebut sudah menjadi wali di kelas lain.'
        ]);

        Kelas::create($request->only('nama_kelas', 'wali_kelas_id'));

        return redirect()->route('admin.kelas.index')->with('success', 'Data Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kela)
    {
        $kelas = $kela;
        $assignedGuruIds = Kelas::whereNotNull('wali_kelas_id')
                                ->where('id', '!=', $kela->id)
                                ->pluck('wali_kelas_id')->toArray();
        $gurus = Guru::whereNotIn('id', $assignedGuruIds)->get();
        return view('admin.kelas.edit', compact('kelas', 'gurus'));
    }

    public function update(Request $request, Kelas $kela)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:100|unique:kelas,nama_kelas,' . $kela->id,
            'wali_kelas_id' => 'nullable|exists:guru,id|unique:kelas,wali_kelas_id,' . $kela->id,
        ], [
            'wali_kelas_id.unique' => 'Guru tersebut sudah menjadi wali di kelas lain.'
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
