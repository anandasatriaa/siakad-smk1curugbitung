<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Guru;
use Illuminate\Http\Request;

class JadwalPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $semester = $request->input('semester', 'Ganjil');
        $tahun_ajaran = $request->input('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));

        $tahun_ajaran_options = [];
        $currentYear = date('Y');
        for ($i = -2; $i <= 2; $i++) {
            $year = $currentYear + $i;
            $tahun_ajaran_options[] = $year . '/' . ($year + 1);
        }

        $jadwals = JadwalPelajaran::with(['kelas', 'mata_pelajaran', 'guru'])
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->join('kelas', 'jadwal_pelajaran.kelas_id', '=', 'kelas.id')
            ->select('jadwal_pelajaran.*')
            ->orderBy('kelas.nama_kelas')
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy(function($item) {
                return $item->kelas->nama_kelas;
            });
            
        return view('admin.jadwal.index', compact('jadwals', 'semester', 'tahun_ajaran', 'tahun_ajaran_options'));
    } 

    public function create()
    {
        $kelases = Kelas::all();
        $mapels = MataPelajaran::all();
        $gurus = Guru::all();

        $hari_options = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        
        $tahun_ajaran_options = [];
        $currentYear = date('Y');
        for ($i = -2; $i <= 2; $i++) {
            $year = $currentYear + $i;
            $tahun_ajaran_options[] = $year . '/' . ($year + 1);
        }

        return view('admin.jadwal.create', compact('kelases', 'mapels', 'gurus', 'hari_options', 'tahun_ajaran_options'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'hari' => 'required|string',
            'tahun_ajaran' => 'required|string',
            'semester' => 'required|in:Ganjil,Genap',
            'mapel_id' => 'required|array',
            'mapel_id.*' => 'required|exists:mata_pelajaran,id',
            'guru_id' => 'required|array',
            'guru_id.*' => 'required|exists:guru,id',
            'jam_mulai' => 'required|array',
            'jam_mulai.*' => 'required|date_format:H:i',
            'jam_selesai' => 'required|array',
            'jam_selesai.*' => 'required|date_format:H:i',
        ]);

        foreach ($request->mapel_id as $key => $mapel_id) {
            JadwalPelajaran::create([
                'kelas_id' => $request->kelas_id,
                'hari' => $request->hari,
                'tahun_ajaran' => $request->tahun_ajaran,
                'semester' => $request->semester,
                'mapel_id' => $mapel_id,
                'guru_id' => $request->guru_id[$key],
                'jam_mulai' => $request->jam_mulai[$key],
                'jam_selesai' => $request->jam_selesai[$key],
            ]);
        }

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal Pelajaran berhasil ditambahkan.');
    }

    public function edit(JadwalPelajaran $jadwal)
    {
        $kelases = Kelas::all();
        $mapels = MataPelajaran::all();
        $gurus = Guru::all();

        $hari_options = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        $tahun_ajaran_options = [];
        $currentYear = date('Y');
        for ($i = -2; $i <= 2; $i++) {
            $year = $currentYear + $i;
            $tahun_ajaran_options[] = $year . '/' . ($year + 1);
        }

        $jadwals = JadwalPelajaran::where('kelas_id', $jadwal->kelas_id)
            ->where('hari', $jadwal->hari)
            ->where('tahun_ajaran', $jadwal->tahun_ajaran)
            ->where('semester', $jadwal->semester)
            ->orderBy('jam_mulai')
            ->get();

        return view('admin.jadwal.edit', compact('jadwal', 'jadwals', 'kelases', 'mapels', 'gurus', 'hari_options', 'tahun_ajaran_options'));
    }

    public function update(Request $request, JadwalPelajaran $jadwal)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'hari' => 'required|string',
            'tahun_ajaran' => 'required|string',
            'semester' => 'required|in:Ganjil,Genap',
            'mapel_id' => 'required|array',
            'mapel_id.*' => 'required|exists:mata_pelajaran,id',
            'guru_id' => 'required|array',
            'guru_id.*' => 'required|exists:guru,id',
            'jam_mulai' => 'required|array',
            'jam_mulai.*' => 'required|date_format:H:i',
            'jam_selesai' => 'required|array',
            'jam_selesai.*' => 'required|date_format:H:i',
        ]);

        $old_kelas_id = $jadwal->kelas_id;
        $old_hari = $jadwal->hari;
        $old_tahun = $jadwal->tahun_ajaran;
        $old_semester = $jadwal->semester;

        JadwalPelajaran::where('kelas_id', $old_kelas_id)
            ->where('hari', $old_hari)
            ->where('tahun_ajaran', $old_tahun)
            ->where('semester', $old_semester)
            ->delete();

        foreach ($request->mapel_id as $key => $mapel_id) {
            JadwalPelajaran::create([
                'kelas_id' => $request->kelas_id,
                'hari' => $request->hari,
                'tahun_ajaran' => $request->tahun_ajaran,
                'semester' => $request->semester,
                'mapel_id' => $mapel_id,
                'guru_id' => $request->guru_id[$key],
                'jam_mulai' => $request->jam_mulai[$key],
                'jam_selesai' => $request->jam_selesai[$key],
            ]);
        }

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal Pelajaran berhasil diperbarui.');
    }

    public function destroy(JadwalPelajaran $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal Pelajaran berhasil dihapus.');
    }
}
