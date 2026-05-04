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
        $semester = $request->input('semester', 'Ganjil');
        $tahun_ajaran = $request->input('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));
        
        $siswas = [];
        $mapels = [];
        $nilaiData = [];

        if ($kelas_id) {
            $siswas = Siswa::where('kelas_id', $kelas_id)->get();
            
            $mapelIds = JadwalPelajaran::where('kelas_id', $kelas_id)->pluck('mapel_id')->unique();
            $mapels = MataPelajaran::whereIn('id', $mapelIds)->get();

            if ($mapels->isEmpty()) {
                $mapelIdsFromNilai = Nilai::whereIn('siswa_id', $siswas->pluck('id'))
                    ->where('semester', $semester)
                    ->where('tahun_ajaran', $tahun_ajaran)
                    ->pluck('mapel_id')
                    ->unique();
                $mapels = MataPelajaran::whereIn('id', $mapelIdsFromNilai)->get();
            }

            $nilais = Nilai::whereIn('siswa_id', $siswas->pluck('id'))
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahun_ajaran)
                ->get();
                
            foreach ($nilais as $n) {
                $nilaiData[$n->siswa_id][$n->mapel_id] = $n;
            }
        }

        return view('laporan.nilai', compact('kelasList', 'kelas_id', 'semester', 'tahun_ajaran', 'siswas', 'mapels', 'nilaiData'));
    }

    public function absensi(Request $request)
    {
        $kelasList = Kelas::all();
        
        $kelas_id = $request->input('kelas_id');
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        
        $siswas = [];
        $absensiData = [];

        if ($kelas_id) {
            $siswas = Siswa::where('kelas_id', $kelas_id)->get();
            
            $absensis = Absensi::where('kelas_id', $kelas_id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
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

        return view('laporan.absensi', compact('kelasList', 'kelas_id', 'bulan', 'tahun', 'siswas', 'absensiData'));
    }
}
