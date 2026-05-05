<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $tahun_ajaran = $request->input('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));
        $semester = $request->input('semester', 'Ganjil');

        $tahun_ajaran_options = [];
        $currentYear = date('Y');
        for ($i = -2; $i <= 2; $i++) {
            $year = $currentYear + $i;
            $tahun_ajaran_options[] = $year . '/' . ($year + 1);
        }

        $kelases = Kelas::with('wali_kelas')
            ->where('tahun_ajaran', $tahun_ajaran)
            ->where('semester', $semester)
            ->latest()
            ->get();
            
        return view('admin.kelas.index', compact('kelases', 'tahun_ajaran', 'semester', 'tahun_ajaran_options'));
    }

    public function create()
    {
        $assignedGuruIds = Kelas::whereNotNull('wali_kelas_id')->pluck('wali_kelas_id')->toArray();
        $gurus = Guru::whereNotIn('id', $assignedGuruIds)->get();

        $tahun_ajaran_options = [];
        $currentYear = date('Y');
        for ($i = -2; $i <= 2; $i++) {
            $year = $currentYear + $i;
            $tahun_ajaran_options[] = $year . '/' . ($year + 1);
        }

        return view('admin.kelas.create', compact('gurus', 'tahun_ajaran_options'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => [
                'required', 
                'string', 
                'max:100', 
                function ($attribute, $value, $fail) use ($request) {
                    $exists = Kelas::where('nama_kelas', $value)
                        ->where('tahun_ajaran', $request->tahun_ajaran)
                        ->where('semester', $request->semester)
                        ->exists();
                    if ($exists) {
                        $fail('Nama kelas sudah ada untuk tahun ajaran dan semester ini.');
                    }
                }
            ],
            'wali_kelas_id' => 'nullable|exists:guru,id|unique:kelas,wali_kelas_id',
            'tahun_ajaran' => 'required|string',
            'semester' => 'required|in:Ganjil,Genap',
        ], [
            'wali_kelas_id.unique' => 'Guru tersebut sudah menjadi wali di kelas lain.'
        ]);

        Kelas::create($request->only('nama_kelas', 'wali_kelas_id', 'tahun_ajaran', 'semester'));

        return redirect()->route('admin.kelas.index', [
            'tahun_ajaran' => $request->tahun_ajaran,
            'semester' => $request->semester
        ])->with('success', 'Data Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kela)
    {
        $kelas = $kela;
        $assignedGuruIds = Kelas::whereNotNull('wali_kelas_id')
                                ->where('id', '!=', $kela->id)
                                ->pluck('wali_kelas_id')->toArray();
        $gurus = Guru::whereNotIn('id', $assignedGuruIds)->get();

        $tahun_ajaran_options = [];
        $currentYear = date('Y');
        for ($i = -2; $i <= 2; $i++) {
            $year = $currentYear + $i;
            $tahun_ajaran_options[] = $year . '/' . ($year + 1);
        }

        return view('admin.kelas.edit', compact('kelas', 'gurus', 'tahun_ajaran_options'));
    }

    public function update(Request $request, Kelas $kela)
    {
        $request->validate([
            'nama_kelas' => [
                'required', 
                'string', 
                'max:100', 
                function ($attribute, $value, $fail) use ($request, $kela) {
                    $exists = Kelas::where('nama_kelas', $value)
                        ->where('tahun_ajaran', $request->tahun_ajaran)
                        ->where('semester', $request->semester)
                        ->where('id', '!=', $kela->id)
                        ->exists();
                    if ($exists) {
                        $fail('Nama kelas sudah ada untuk tahun ajaran dan semester ini.');
                    }
                }
            ],
            'wali_kelas_id' => 'nullable|exists:guru,id|unique:kelas,wali_kelas_id,' . $kela->id,
            'tahun_ajaran' => 'required|string',
            'semester' => 'required|in:Ganjil,Genap',
        ], [
            'wali_kelas_id.unique' => 'Guru tersebut sudah menjadi wali di kelas lain.'
        ]);

        $kela->update($request->only('nama_kelas', 'wali_kelas_id', 'tahun_ajaran', 'semester'));

        return redirect()->route('admin.kelas.index', [
            'tahun_ajaran' => $request->tahun_ajaran,
            'semester' => $request->semester
        ])->with('success', 'Data Kelas berhasil diubah.');
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();
        return redirect()->route('admin.kelas.index')->with('success', 'Data Kelas berhasil dihapus.');
    }
}
