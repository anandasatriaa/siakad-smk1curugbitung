<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAkademik;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PeriodeAkademikController extends Controller
{
    public function index()
    {
        $periodes = PeriodeAkademik::orderBy('tahun_ajaran', 'desc')->orderBy('semester', 'desc')->get();
        return view('admin.periode.index', compact('periodes'));
    }

    public function create()
    {
        return view('admin.periode.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => [
                'required',
                'string',
                'max:20',
                Rule::unique('periode_akademik')->where(function ($query) use ($request) {
                    return $query->where('semester', $request->semester);
                }),
            ],
            'semester' => 'required|in:Ganjil,Genap',
            'is_aktif' => 'boolean',
        ], [
            'tahun_ajaran.unique' => 'Tahun ajaran dan semester tersebut sudah ada.',
        ]);

        // Jika periode baru diatur aktif, nonaktifkan periode lainnya
        if ($request->has('is_aktif') && $request->is_aktif) {
            PeriodeAkademik::where('is_aktif', true)->update(['is_aktif' => false]);
        }

        PeriodeAkademik::create($request->all());

        return redirect()->route('admin.periode.index')->with('success', 'Periode Akademik berhasil ditambahkan.');
    }

    public function edit(PeriodeAkademik $periode)
    {
        return view('admin.periode.edit', compact('periode'));
    }

    public function update(Request $request, PeriodeAkademik $periode)
    {
        $request->validate([
            'tahun_ajaran' => [
                'required',
                'string',
                'max:20',
                Rule::unique('periode_akademik')->where(function ($query) use ($request) {
                    return $query->where('semester', $request->semester);
                })->ignore($periode->id),
            ],
            'semester' => 'required|in:Ganjil,Genap',
            'is_aktif' => 'boolean',
        ], [
            'tahun_ajaran.unique' => 'Tahun ajaran dan semester tersebut sudah ada.',
        ]);

        // Jika periode ini diatur aktif, nonaktifkan periode lainnya
        if ($request->has('is_aktif') && $request->is_aktif) {
            PeriodeAkademik::where('id', '!=', $periode->id)->update(['is_aktif' => false]);
        }

        $periode->update($request->all());

        return redirect()->route('admin.periode.index')->with('success', 'Periode Akademik berhasil diperbarui.');
    }

    public function destroy(PeriodeAkademik $periode)
    {
        // Cek jika periode memiliki data terkait (optional, but good for safety)
        if ($periode->kelas()->exists() || $periode->absensi()->exists() || $periode->nilai()->exists()) {
            return redirect()->route('admin.periode.index')->with('error', 'Periode tidak bisa dihapus karena sudah memiliki data terkait (Kelas/Nilai/Absensi).');
        }

        $periode->delete();
        return redirect()->route('admin.periode.index')->with('success', 'Periode Akademik berhasil dihapus.');
    }
}
