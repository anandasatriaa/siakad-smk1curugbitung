<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $tahun_ajaran = $request->input('tahun_ajaran');
        $semester = $request->input('semester');

        // Jika tidak ada filter, ambil periode yang aktif
        if (!$tahun_ajaran || !$semester) {
            $activePeriode = \App\Models\PeriodeAkademik::where('is_aktif', true)->first();
            if ($activePeriode) {
                $tahun_ajaran = $activePeriode->tahun_ajaran;
                $semester = $activePeriode->semester;
            }
        }

        $tahun_ajaran_options = \App\Models\PeriodeAkademik::select('tahun_ajaran')->distinct()->pluck('tahun_ajaran');
        
        $kelases = Kelas::with(['wali_kelas', 'periode'])
            ->whereHas('periode', function($q) use ($tahun_ajaran, $semester) {
                if ($tahun_ajaran) $q->where('tahun_ajaran', $tahun_ajaran);
                if ($semester) $q->where('semester', $semester);
            })
            ->latest()
            ->get();
            
        return view('admin.kelas.index', compact('kelases', 'tahun_ajaran', 'semester', 'tahun_ajaran_options'));
    }

    public function create()
    {
        $assignedGuruIds = Kelas::whereNotNull('wali_kelas_id')->pluck('wali_kelas_id')->toArray();
        $gurus = Guru::whereNotIn('id', $assignedGuruIds)->get();
        $periodes = \App\Models\PeriodeAkademik::orderBy('tahun_ajaran', 'desc')->get();

        return view('admin.kelas.create', compact('gurus', 'periodes'));
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
                        ->where('periode_id', $request->periode_id)
                        ->exists();
                    if ($exists) {
                        $fail('Nama kelas sudah ada untuk periode ini.');
                    }
                }
            ],
            'wali_kelas_id' => 'nullable|exists:guru,id|unique:kelas,wali_kelas_id',
            'periode_id' => 'required|exists:periode_akademik,id',
        ], [
            'wali_kelas_id.unique' => 'Guru tersebut sudah menjadi wali di kelas lain.'
        ]);

        $kelas = Kelas::create($request->only('nama_kelas', 'wali_kelas_id', 'periode_id'));
        $kelas->load('periode');

        return redirect()->route('admin.kelas.index', [
            'tahun_ajaran' => $kelas->periode->tahun_ajaran,
            'semester' => $kelas->periode->semester
        ])->with('success', 'Data Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kela)
    {
        $kelas = $kela;
        $assignedGuruIds = Kelas::whereNotNull('wali_kelas_id')
                                ->where('id', '!=', $kela->id)
                                ->pluck('wali_kelas_id')->toArray();
        $gurus = Guru::whereNotIn('id', $assignedGuruIds)->get();
        $periodes = \App\Models\PeriodeAkademik::orderBy('tahun_ajaran', 'desc')->get();

        return view('admin.kelas.edit', compact('kelas', 'gurus', 'periodes'));
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
                        ->where('periode_id', $request->periode_id)
                        ->where('id', '!=', $kela->id)
                        ->exists();
                    if ($exists) {
                        $fail('Nama kelas sudah ada untuk periode ini.');
                    }
                }
            ],
            'wali_kelas_id' => 'nullable|exists:guru,id|unique:kelas,wali_kelas_id,' . $kela->id,
            'periode_id' => 'required|exists:periode_akademik,id',
        ], [
            'wali_kelas_id.unique' => 'Guru tersebut sudah menjadi wali di kelas lain.'
        ]);

        $kela->update($request->only('nama_kelas', 'wali_kelas_id', 'periode_id'));
        $kela->load('periode');

        return redirect()->route('admin.kelas.index', [
            'tahun_ajaran' => $kela->periode->tahun_ajaran,
            'semester' => $kela->periode->semester
        ])->with('success', 'Data Kelas berhasil diubah.');
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();
        return redirect()->route('admin.kelas.index')->with('success', 'Data Kelas berhasil dihapus.');
    }
}
