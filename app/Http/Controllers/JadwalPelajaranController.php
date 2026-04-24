<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Guru;
use Illuminate\Http\Request;

class JadwalPelajaranController extends Controller
{
    public function index()
    {
        $jadwals = JadwalPelajaran::with(['kelas', 'mata_pelajaran', 'guru'])->orderBy('hari')->orderBy('jam_mulai')->get();
        return view('admin.jadwal.index', compact('jadwals'));
    } 

    public function create()
    {
        $kelases = Kelas::all();
        $mapels = MataPelajaran::all();
        $gurus = Guru::all();

        $hari_options = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('admin.jadwal.create', compact('kelases', 'mapels', 'gurus', 'hari_options'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'hari' => 'required|string',
            'mapel_id' => 'required|array',
            'mapel_id.*' => 'required|exists:mata_pelajarans,id',
            'guru_id' => 'required|array',
            'guru_id.*' => 'required|exists:gurus,id',
            'jam_mulai' => 'required|array',
            'jam_mulai.*' => 'required|date_format:H:i',
            'jam_selesai' => 'required|array',
            'jam_selesai.*' => 'required|date_format:H:i',
        ]);

        foreach ($request->mapel_id as $key => $mapel_id) {
            JadwalPelajaran::create([
                'kelas_id' => $request->kelas_id,
                'hari' => $request->hari,
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

        $jadwals = JadwalPelajaran::where('kelas_id', $jadwal->kelas_id)
            ->where('hari', $jadwal->hari)
            ->orderBy('jam_mulai')
            ->get();

        return view('admin.jadwal.edit', compact('jadwal', 'jadwals', 'kelases', 'mapels', 'gurus', 'hari_options'));
    }

    public function update(Request $request, JadwalPelajaran $jadwal)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'hari' => 'required|string',
            'mapel_id' => 'required|array',
            'mapel_id.*' => 'required|exists:mata_pelajarans,id',
            'guru_id' => 'required|array',
            'guru_id.*' => 'required|exists:gurus,id',
            'jam_mulai' => 'required|array',
            'jam_mulai.*' => 'required|date_format:H:i',
            'jam_selesai' => 'required|array',
            'jam_selesai.*' => 'required|date_format:H:i',
        ]);

        $old_kelas_id = $jadwal->kelas_id;
        $old_hari = $jadwal->hari;

        JadwalPelajaran::where('kelas_id', $old_kelas_id)->where('hari', $old_hari)->delete();

        foreach ($request->mapel_id as $key => $mapel_id) {
            JadwalPelajaran::create([
                'kelas_id' => $request->kelas_id,
                'hari' => $request->hari,
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
