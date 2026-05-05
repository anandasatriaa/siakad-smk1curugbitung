<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\Absensi;
use App\Models\MataPelajaran;
use App\Models\JadwalPelajaran;

class LaporanController extends Controller
{
    public function nilai(Request $request)
    {
        $kelasList = Kelas::all();
        
        $kelas_id = $request->input('kelas_id');
        $periode_id = $request->input('periode_id');

        // Jika tidak ada filter periode, ambil yang aktif
        if (!$periode_id) {
            $activePeriode = \App\Models\PeriodeAkademik::where('is_aktif', true)->first();
            $periode_id = $activePeriode ? $activePeriode->id : null;
        }

        $periodes = \App\Models\PeriodeAkademik::orderBy('tahun_ajaran', 'desc')->get();
        
        $siswas = [];
        $mapels = [];
        $nilaiData = [];

        if ($kelas_id) {
            $siswas = Siswa::where('kelas_id', $kelas_id)->get();
            
            $mapelIds = JadwalPelajaran::where('kelas_id', $kelas_id)->pluck('mapel_id')->unique();
            $mapels = MataPelajaran::whereIn('id', $mapelIds)->get();

            if ($mapels->isEmpty()) {
                $mapelIdsFromNilai = Nilai::whereIn('siswa_id', $siswas->pluck('id'))
                    ->where('periode_id', $periode_id)
                    ->pluck('mapel_id')
                    ->unique();
                $mapels = MataPelajaran::whereIn('id', $mapelIdsFromNilai)->get();
            }

            $nilais = Nilai::whereIn('siswa_id', $siswas->pluck('id'))
                ->where('periode_id', $periode_id)
                ->get();
                
            foreach ($nilais as $n) {
                $nilaiData[$n->siswa_id][$n->mapel_id] = $n;
            }
        }

        return view('laporan.nilai', compact('kelasList', 'periodes', 'kelas_id', 'periode_id', 'siswas', 'mapels', 'nilaiData'));
    }

    public function absensi(Request $request)
    {
        $kelasList = Kelas::all();
        
        $kelas_id = $request->input('kelas_id');
        $periode_id = $request->input('periode_id');

        // Jika tidak ada filter periode, ambil yang aktif
        $activePeriode = null;
        if (!$periode_id) {
            $activePeriode = \App\Models\PeriodeAkademik::where('is_aktif', true)->first();
            $periode_id = $activePeriode ? $activePeriode->id : null;
        } else {
            $activePeriode = \App\Models\PeriodeAkademik::find($periode_id);
        }

        $periodes = \App\Models\PeriodeAkademik::orderBy('tahun_ajaran', 'desc')->get();
        
        $siswas = [];
        $absensiData = [];

        if ($kelas_id && $periode_id) {
            $siswas = Siswa::where('kelas_id', $kelas_id)->get();
            
            $absensis = Absensi::where('kelas_id', $kelas_id)
                ->where('periode_id', $periode_id)
                ->get();
                
            foreach ($absensis as $a) {
                if (!isset($absensiData[$a->siswa_id])) {
                    $absensiData[$a->siswa_id] = [
                        'hadir' => 0,
                        'sakit' => 0,
                        'izin' => 0,
                        'alpa' => 0,
                    ];
                }
                
                $status = strtolower($a->status);
                if (isset($absensiData[$a->siswa_id][$status])) {
                    $absensiData[$a->siswa_id][$status]++;
                }
            }
        }

        return view('laporan.absensi', compact('kelasList', 'periodes', 'activePeriode', 'kelas_id', 'periode_id', 'siswas', 'absensiData'));
    }
}
