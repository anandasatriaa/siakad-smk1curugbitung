<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Authorization check
        $isSuperadmin = $user->role === 'superadmin';
        $isSekretaris = false;
        $siswaLog = null;

        if ($user->role === 'siswa') {
            $siswaLog = Siswa::where('user_id', $user->id)->first();
            if ($siswaLog && strtolower($siswaLog->jabatan) === 'sekretaris') {
                $isSekretaris = true;
            }
        }

        if (!$isSuperadmin && !$isSekretaris) {
            abort(403, 'Unauthorized action.');
        }

        // Get input parameters
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        
        // Determine allowed class
        $kelas_id = $request->input('kelas_id');
        $kelasList = [];

        if ($isSuperadmin) {
            $kelasList = Kelas::all();
        } else {
            $kelasList = Kelas::where('id', $siswaLog->kelas_id)->get();
            // Force the class ID to the student's class
            $kelas_id = $siswaLog->kelas_id;
        }

        $siswas = [];
        $absensiExisting = [];

        if ($kelas_id && $tanggal) {
            $siswas = Siswa::where('kelas_id', $kelas_id)->get();
            
            // Fetch existing attendance to pre-fill the form
            $absensi = Absensi::where('kelas_id', $kelas_id)
                ->where('tanggal', $tanggal)
                ->get();
            
            foreach ($absensi as $ab) {
                $absensiExisting[$ab->siswa_id] = $ab;
            }
        }

        return view('absensi.index', compact(
            'isSuperadmin', 'kelasList', 'kelas_id', 'tanggal', 'siswas', 'absensiExisting'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Not used, handled in index
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal' => 'required|date|before_or_equal:today',
            'absensi' => 'required|array',
            'absensi.*.status' => 'required|in:hadir,sakit,izin,alpa',
            'absensi.*.keterangan' => 'nullable|string|max:255',
        ]);

        // Authorization logic
        $user = Auth::user();
        $isSuperadmin = $user->role === 'superadmin';
        $isSekretaris = false;
        if ($user->role === 'siswa') {
            $siswaLog = Siswa::where('user_id', $user->id)->first();
            if ($siswaLog && strtolower($siswaLog->jabatan) === 'sekretaris' && $siswaLog->kelas_id == $request->kelas_id) {
                $isSekretaris = true;
            }
        }

        if (!$isSuperadmin && !$isSekretaris) {
            abort(403, 'Unauthorized action.');
        }

        foreach ($request->absensi as $siswa_id => $data) {
            Absensi::updateOrCreate(
                [
                    'kelas_id' => $request->kelas_id,
                    'siswa_id' => $siswa_id,
                    'tanggal' => $request->tanggal,
                ],
                [
                    'status' => $data['status'],
                    'keterangan' => $data['keterangan'] ?? null,
                ]
            );
        }

        return redirect()->route('siswa.absensi.index', [
            'kelas_id' => $request->kelas_id, 
            'tanggal' => $request->tanggal
        ])->with('success', 'Data absensi berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Absensi $absensi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absensi $absensi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absensi $absensi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absensi $absensi)
    {
        //
    }
}
