<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\JadwalPelajaran;
use App\Models\Absensi;
use App\Models\PeriodeAkademik;
use App\Models\RiwayatKelas;
use App\Models\Nilai;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $semuaPeriode = PeriodeAkademik::orderBy('tahun_ajaran', 'desc')->orderBy('semester', 'desc')->get();
        
        $periodeId = $request->get('periode_id');
        if ($periodeId) {
            $aktifPeriode = PeriodeAkademik::find($periodeId);
        } else {
            $aktifPeriode = PeriodeAkademik::where('is_aktif', true)->first();
        }

        if (!$aktifPeriode && $semuaPeriode->isNotEmpty()) {
            $aktifPeriode = $semuaPeriode->first();
        }

        $daysIndo = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        $todayIndo = $daysIndo[Carbon::now()->format('l')];
        
        switch ($user->role) {
            case 'superadmin':
            case 'admin':
                $totalGuru = Guru::count();
                $totalSiswa = Siswa::where(function($q) use ($aktifPeriode) {
                    $q->whereHas('riwayatKelas', function ($query) use ($aktifPeriode) {
                        $query->where('periode_id', optional($aktifPeriode)->id);
                    })->orWhereHas('kelas', function ($query) use ($aktifPeriode) {
                        $query->where('periode_id', optional($aktifPeriode)->id);
                    });
                })->count();
                
                $totalKelas = Kelas::where('periode_id', optional($aktifPeriode)->id)->count();
                $totalMapel = MataPelajaran::count();

                $kehadiranHariIni = [
                    'hadir' => Absensi::where('tanggal', Carbon::today())->where('status', 'hadir')->count(),
                    'sakit' => Absensi::where('tanggal', Carbon::today())->where('status', 'sakit')->count(),
                    'izin' => Absensi::where('tanggal', Carbon::today())->where('status', 'izin')->count(),
                    'alpa' => Absensi::where('tanggal', Carbon::today())->where('status', 'alpa')->count(),
                ];

                $distribusiSiswa = Kelas::where('periode_id', optional($aktifPeriode)->id)
                    ->withCount('siswa')
                    ->get();

                return view('dashboard.admin', compact('totalGuru', 'totalSiswa', 'totalKelas', 'totalMapel', 'aktifPeriode', 'semuaPeriode', 'kehadiranHariIni', 'distribusiSiswa'));

            case 'guru':
                $guru = $user->guru;
                $kelasWaliCount = 0;
                $jadwalHariIni = collect();

                if ($guru && $aktifPeriode) {
                    $kelasWaliCount = Kelas::where('wali_kelas_id', $guru->id)
                        ->where('periode_id', $aktifPeriode->id)->count();

                    $jadwalHariIni = JadwalPelajaran::where('guru_id', $guru->id)
                        ->where('hari', $todayIndo)
                        ->whereHas('kelas', function ($query) use ($aktifPeriode) {
                            $query->where('periode_id', $aktifPeriode->id);
                        })
                        ->with(['kelas', 'mata_pelajaran'])
                        ->get();
                }
                return view('dashboard.guru', compact('guru', 'aktifPeriode', 'semuaPeriode', 'kelasWaliCount', 'jadwalHariIni'));

            case 'siswa':
                $siswa = $user->siswa;
                $sakit = 0;
                $izin = 0;
                $alpa = 0;
                $kelasSiswa = null;
                $jadwalHariIni = collect();
                $nilaiTerbaru = collect();
                $rataRataNilai = 0;

                if ($siswa && $aktifPeriode) {
                    $sakit = Absensi::where('siswa_id', $siswa->id)->where('periode_id', $aktifPeriode->id)->where('status', 'sakit')->count();
                    $izin = Absensi::where('siswa_id', $siswa->id)->where('periode_id', $aktifPeriode->id)->where('status', 'izin')->count();
                    $alpa = Absensi::where('siswa_id', $siswa->id)->where('periode_id', $aktifPeriode->id)->where('status', 'alpa')->count();
                    
                    $riwayat = RiwayatKelas::with(['kelas.wali_kelas'])->where('siswa_id', $siswa->id)
                        ->where('periode_id', $aktifPeriode->id)
                        ->first();
                    
                    if ($riwayat) {
                        $kelasSiswa = $riwayat->kelas;
                    } else {
                        if ($siswa->kelas && $siswa->kelas->periode_id == $aktifPeriode->id) {
                            $kelasSiswa = $siswa->kelas;
                        }
                    }

                    if ($kelasSiswa) {
                        $jadwalHariIni = JadwalPelajaran::where('kelas_id', $kelasSiswa->id)
                            ->where('hari', $todayIndo)
                            ->with(['mata_pelajaran', 'guru'])
                            ->get();
                    }

                    $nilaiTerbaru = Nilai::where('siswa_id', $siswa->id)
                        ->where('periode_id', $aktifPeriode->id)
                        ->with('mataPelajaran')
                        ->latest()
                        ->take(5)
                        ->get();

                    $rataRataNilai = Nilai::where('siswa_id', $siswa->id)
                        ->where('periode_id', $aktifPeriode->id)
                        ->avg('nilai_akhir');
                }
                return view('dashboard.siswa', compact('siswa', 'aktifPeriode', 'semuaPeriode', 'sakit', 'izin', 'alpa', 'kelasSiswa', 'jadwalHariIni', 'nilaiTerbaru', 'rataRataNilai'));
            default:
                return view('dashboard.default');
        }
    }
}
