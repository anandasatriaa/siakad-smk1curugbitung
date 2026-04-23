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
            'mapel_id' => 'required|exists:mata_pelajarans,id',
            'guru_id' => 'required|exists:gurus,id',
            'hari' => 'required|string',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        JadwalPelajaran::create($request->only('kelas_id', 'mapel_id', 'guru_id', 'hari', 'jam_mulai', 'jam_selesai'));

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal Pelajaran berhasil ditambahkan.');
    }

    public function edit(JadwalPelajaran $jadwal)
    {
        $kelases = Kelas::all();
        $mapels = MataPelajaran::all();
        $gurus = Guru::all();
        
        $hari_options = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('admin.jadwal.edit', compact('jadwal', 'kelases', 'mapels', 'gurus', 'hari_options'));
    }

    public function update(Request $request, JadwalPelajaran $jadwal)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mata_pelajarans,id',
            'guru_id' => 'required|exists:gurus,id',
            'hari' => 'required|string',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $jadwal->update($request->only('kelas_id', 'mapel_id', 'guru_id', 'hari', 'jam_mulai', 'jam_selesai'));

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal Pelajaran berhasil diubah.');
    }

    public function destroy(JadwalPelajaran $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal Pelajaran berhasil dihapus.');
    }
}
