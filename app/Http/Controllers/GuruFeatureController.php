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

    public function jadwalMengajar()
    {
        $user = Auth::user();
        if ($user->role === 'superadmin') {
            $jadwals = JadwalPelajaran::with(['kelas', 'mata_pelajaran', 'guru'])->orderBy('hari')->orderBy('jam_mulai')->get();
        } else {
            $guru = $this->getGuru();
            $jadwals = JadwalPelajaran::with(['kelas', 'mata_pelajaran'])
                ->where('guru_id', $guru->id)
                ->orderBy('hari')
                ->orderBy('jam_mulai')
                ->get();
        }

        return view('guru.jadwal_mengajar', compact('jadwals'));
    }

    public function siswaKelas(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'superadmin') {
            $kelasList = Kelas::all();
        } else {
            $guru = $this->getGuru();
            $kelasIds = JadwalPelajaran::where('guru_id', $guru->id)->pluck('kelas_id')->unique();
            $kelasList = Kelas::whereIn('id', $kelasIds)->get();
        }

        $kelas_id = $request->input('kelas_id');
        $siswas = [];

        if ($kelas_id) {
            $siswas = Siswa::where('kelas_id', $kelas_id)->get();
        }

        return view('guru.siswa_kelas', compact('kelasList', 'kelas_id', 'siswas'));
    }

    public function riwayatAbsensi(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'superadmin') {
            $kelasList = Kelas::all();
        } else {
            $guru = $this->getGuru();
            $kelasIds = JadwalPelajaran::where('guru_id', $guru->id)->pluck('kelas_id')->unique();
            $kelasList = Kelas::whereIn('id', $kelasIds)->get();
        }

        $kelas_id = $request->input('kelas_id');
        $tanggal_awal = $request->input('tanggal_awal', date('Y-m-d', strtotime('-7 days')));
        $tanggal_akhir = $request->input('tanggal_akhir', date('Y-m-d'));

        $absensis = [];

        if ($kelas_id) {
            $absensis = Absensi::with('siswa')
                ->where('kelas_id', $kelas_id)
                ->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir])
                ->orderBy('tanggal', 'desc')
                ->get();
        }

        return view('guru.riwayat_absensi', compact('kelasList', 'kelas_id', 'tanggal_awal', 'tanggal_akhir', 'absensis'));
    }

    public function exportLaporan(Request $request)
    {
        $user = Auth::user();
        if ($user->role === 'superadmin') {
            $kelasList = Kelas::all();
        } else {
            $guru = $this->getGuru();
            // Wali kelas exports reports. Or allow to export for any class they teach? 
            // The prompt says "export laporan nya isinya adalah hasil seperti raport siswa per semester". Raport usually accessed by wali kelas. Let's make it Wali Kelas only or superadmin.
            $kelasList = Kelas::where('wali_kelas_id', $guru->id)->get();
        }

        $kelas_id = $request->input('kelas_id');
        $semester = $request->input('semester', 'Ganjil');
        $tahun_ajaran = $request->input('tahun_ajaran', date('Y') . '/' . (date('Y')+1));

        $siswas = [];
        if ($kelas_id) {
            $siswas = Siswa::where('kelas_id', $kelas_id)->get();
        }

        return view('guru.export_laporan', compact('kelasList', 'kelas_id', 'semester', 'tahun_ajaran', 'siswas'));
    }

    public function downloadRaport(Request $request)
    {
        $siswa_id = $request->input('siswa_id');
        $semester = $request->input('semester');
        $tahun_ajaran = $request->input('tahun_ajaran');

        $siswa = Siswa::with('kelas')->findOrFail($siswa_id);
        
        $nilais = Nilai::with('mataPelajaran')
            ->where('siswa_id', $siswa_id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->get();
            
        $absensiSummary = [
            'hadir' => Absensi::where('siswa_id', $siswa_id)->where('status', 'hadir')->count(),
            'sakit' => Absensi::where('siswa_id', $siswa_id)->where('status', 'sakit')->count(),
            'izin' => Absensi::where('siswa_id', $siswa_id)->where('status', 'izin')->count(),
            'alpa' => Absensi::where('siswa_id', $siswa_id)->where('status', 'alpa')->count(),
        ];

        $pdf = Pdf::loadView('guru.laporan_pdf', compact('siswa', 'nilais', 'semester', 'tahun_ajaran', 'absensiSummary'));
        
        return $pdf->download('Laporan_' . str_replace(' ', '_', $siswa->nama_siswa) . '_' . $semester . '.pdf');
    }
}
