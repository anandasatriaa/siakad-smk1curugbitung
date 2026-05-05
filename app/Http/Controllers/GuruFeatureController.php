<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalPelajaran;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Nilai;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class GuruFeatureController extends Controller
{
    private function getGuru()
    {
        $user = Auth::user();
        if ($user->role === 'superadmin') {
            return null;
        }
        return Guru::where('user_id', $user->id)->firstOrFail();
    }

    public function jadwalMengajar(Request $request)
    {
        $periode_id = $request->input('periode_id');

        // Jika tidak ada filter periode, ambil yang aktif
        if (!$periode_id) {
            $activePeriode = \App\Models\PeriodeAkademik::where('is_aktif', true)->first();
            $periode_id = $activePeriode ? $activePeriode->id : null;
        }

        $periodes = \App\Models\PeriodeAkademik::orderBy('tahun_ajaran', 'desc')->get();

        $query = JadwalPelajaran::with(['kelas.periode', 'mata_pelajaran', 'guru'])
            ->whereHas('kelas', function($q) use ($periode_id) {
                if ($periode_id) $q->where('periode_id', $periode_id);
            })
            ->orderBy('hari')
            ->orderBy('jam_mulai');

        $user = Auth::user();
        if ($user->role !== 'superadmin') {
            $guru = $this->getGuru();
            $query->where('guru_id', $guru->id);
        }

        $jadwals = $query->get();

        return view('guru.jadwal_mengajar', compact('jadwals', 'periode_id', 'periodes'));
    }

    public function siswaKelas(Request $request)
    {
        $periode_id = $request->input('periode_id');

        // Jika tidak ada filter periode, ambil yang aktif
        if (!$periode_id) {
            $activePeriode = \App\Models\PeriodeAkademik::where('is_aktif', true)->first();
            $periode_id = $activePeriode ? $activePeriode->id : null;
        }

        $periodes = \App\Models\PeriodeAkademik::orderBy('tahun_ajaran', 'desc')->get();

        $user = Auth::user();
        if ($user->role === 'superadmin') {
            $kelasList = Kelas::where('periode_id', $periode_id)->get();
        } else {
            $guru = $this->getGuru();
            // Ambil kelas yang diajar guru pada periode tertentu
            $kelasIds = JadwalPelajaran::where('guru_id', $guru->id)
                ->whereHas('kelas', function($q) use ($periode_id) {
                    $q->where('periode_id', $periode_id);
                })
                ->pluck('kelas_id')
                ->unique();
            $kelasList = Kelas::whereIn('id', $kelasIds)->get();
        }

        $kelas_id = $request->input('kelas_id');
        $siswas = [];

        if ($kelas_id) {
            $siswas = Siswa::where('kelas_id', $kelas_id)->get();
        }

        return view('guru.siswa_kelas', compact('kelasList', 'kelas_id', 'siswas', 'periode_id', 'periodes'));
    }

    public function riwayatAbsensi(Request $request)
    {
        $periode_id = $request->input('periode_id');

        // Jika tidak ada filter periode, ambil yang aktif
        if (!$periode_id) {
            $activePeriode = \App\Models\PeriodeAkademik::where('is_aktif', true)->first();
            $periode_id = $activePeriode ? $activePeriode->id : null;
        }

        $periodes = \App\Models\PeriodeAkademik::orderBy('tahun_ajaran', 'desc')->get();

        $user = Auth::user();
        if ($user->role === 'superadmin') {
            $kelasList = Kelas::where('periode_id', $periode_id)->get();
        } else {
            $guru = $this->getGuru();
            // Ambil kelas yang diajar guru pada periode tertentu
            $kelasIds = JadwalPelajaran::where('guru_id', $guru->id)
                ->whereHas('kelas', function($q) use ($periode_id) {
                    $q->where('periode_id', $periode_id);
                })
                ->pluck('kelas_id')
                ->unique();
            $kelasList = Kelas::whereIn('id', $kelasIds)->get();
        }

        $kelas_id = $request->input('kelas_id');
        $tanggal_awal = $request->input('tanggal_awal', date('Y-m-d', strtotime('-7 days')));
        $tanggal_akhir = $request->input('tanggal_akhir', date('Y-m-d'));

        $absensis = [];

        if ($kelas_id) {
            $absensis = Absensi::with('siswa')
                ->where('kelas_id', $kelas_id)
                ->where('periode_id', $periode_id)
                ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
                ->orderBy('tanggal', 'desc')
                ->get();
        }

        return view('guru.riwayat_absensi', compact('kelasList', 'periodes', 'periode_id', 'kelas_id', 'tanggal_awal', 'tanggal_akhir', 'absensis'));
    }

    public function exportLaporan(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'superadmin') {
            $kelasList = Kelas::all();
        } else {
            $guru = $this->getGuru();
            $kelasList = Kelas::where('wali_kelas_id', $guru->id)->get();
        }

        $kelas_id = $request->input('kelas_id');
        $periode_id = $request->input('periode_id');

        // Jika tidak ada filter periode, ambil yang aktif
        if (!$periode_id) {
            $activePeriode = \App\Models\PeriodeAkademik::where('is_aktif', true)->first();
            $periode_id = $activePeriode ? $activePeriode->id : null;
        }

        $periodes = \App\Models\PeriodeAkademik::orderBy('tahun_ajaran', 'desc')->get();

        $siswas = [];
        if ($kelas_id) {
            $siswas = Siswa::where('kelas_id', $kelas_id)->get();
        }

        return view('guru.export_laporan', compact('kelasList', 'kelas_id', 'periode_id', 'periodes', 'siswas'));
    }

    public function downloadRaport(Request $request)
    {
        $siswa_id = $request->input('siswa_id');
        $periode_id = $request->input('periode_id');

        $siswa = Siswa::with('kelas')->findOrFail($siswa_id);
        $periode = \App\Models\PeriodeAkademik::findOrFail($periode_id);
        
        $nilais = Nilai::with('mataPelajaran')
            ->where('siswa_id', $siswa_id)
            ->where('periode_id', $periode_id)
            ->get();
            
        $absensiSummary = [
            'hadir' => Absensi::where('siswa_id', $siswa_id)->where('periode_id', $periode_id)->where('status', 'hadir')->count(),
            'sakit' => Absensi::where('siswa_id', $siswa_id)->where('periode_id', $periode_id)->where('status', 'sakit')->count(),
            'izin' => Absensi::where('siswa_id', $siswa_id)->where('periode_id', $periode_id)->where('status', 'izin')->count(),
            'alpa' => Absensi::where('siswa_id', $siswa_id)->where('periode_id', $periode_id)->where('status', 'alpa')->count(),
        ];

        $semester = $periode->semester;
        $tahun_ajaran = $periode->tahun_ajaran;

        $pdf = Pdf::loadView('guru.laporan_pdf', compact('siswa', 'nilais', 'semester', 'tahun_ajaran', 'absensiSummary'));
        
        return $pdf->download('Laporan_' . str_replace(' ', '_', $siswa->nama_siswa) . '_' . $semester . '.pdf');
    }
}
