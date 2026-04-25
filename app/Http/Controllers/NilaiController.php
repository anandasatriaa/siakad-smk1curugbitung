<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\JadwalPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isSuperadminOrAdmin = in_array($user->role, ['superadmin', 'admin']);
        $guruLog = null;

        if ($isSuperadminOrAdmin) {
            $kelasList = Kelas::all();
            $mapelList = MataPelajaran::all();
        } else {
            $guruLog = Guru::where('user_id', $user->id)->first();
            if (!$guruLog) {
                abort(403, 'Data Guru tidak ditemukan.');
            }
            
            $kelasIds = JadwalPelajaran::where('guru_id', $guruLog->id)->pluck('kelas_id');
            $mapelIds = JadwalPelajaran::where('guru_id', $guruLog->id)->pluck('mapel_id');
            
            $kelasList = Kelas::whereIn('id', $kelasIds)->get();
            $mapelList = MataPelajaran::whereIn('id', $mapelIds)->get();
        }

        $kelas_id = $request->input('kelas_id');
        $mapel_id = $request->input('mapel_id');
        $semester = $request->input('semester', 'Ganjil');
        $tahun_ajaran = $request->input('tahun_ajaran', date('Y') . '/' . (date('Y') + 1));

        $siswas = [];
        $nilaiExisting = [];

        if ($kelas_id && $mapel_id) {
            if (!$isSuperadminOrAdmin) {
                $valid = JadwalPelajaran::where('guru_id', $guruLog->id)
                    ->where('kelas_id', $kelas_id)
                    ->where('mapel_id', $mapel_id)
                    ->exists();
                if (!$valid) {
                    abort(403, 'Anda tidak mengajar mata pelajaran ini di kelas tersebut.');
                }
            }

            $siswas = Siswa::where('kelas_id', $kelas_id)->get();

            $nilais = Nilai::whereIn('siswa_id', $siswas->pluck('id'))
                ->where('mapel_id', $mapel_id)
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahun_ajaran)
                ->get();

            foreach ($nilais as $n) {
                $nilaiExisting[$n->siswa_id] = $n->nilai_akhir;
            }
        }

        return view('nilai.index', compact(
            'isSuperadminOrAdmin', 'kelasList', 'mapelList', 'kelas_id', 'mapel_id',
            'semester', 'tahun_ajaran', 'siswas', 'nilaiExisting'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mata_pelajarans,id',
            'semester' => 'required|in:Ganjil,Genap',
            'tahun_ajaran' => 'required|string',
            'nilai' => 'required|array',
            'nilai.*' => 'nullable|numeric|min:0|max:100',
        ]);

        $user = Auth::user();
        $isSuperadminOrAdmin = in_array($user->role, ['superadmin', 'admin']);

        if (!$isSuperadminOrAdmin) {
            $guruLog = Guru::where('user_id', $user->id)->first();
            $valid = JadwalPelajaran::where('guru_id', $guruLog->id)
                ->where('kelas_id', $request->kelas_id)
                ->where('mapel_id', $request->mapel_id)
                ->exists();
            if (!$valid) {
                abort(403, 'Anda tidak memiliki akses untuk menyimpan nilai pada kelas dan mata pelajaran ini.');
            }
        }

        foreach ($request->nilai as $siswa_id => $nilai_akhir) {
            if ($nilai_akhir !== null && $nilai_akhir !== '') {
                Nilai::updateOrCreate(
                    [
                        'siswa_id' => $siswa_id,
                        'mapel_id' => $request->mapel_id,
                        'semester' => $request->semester,
                        'tahun_ajaran' => $request->tahun_ajaran,
                    ],
                    [
                        'nilai_akhir' => $nilai_akhir,
                    ]
                );
            }
        }

        return redirect()->route('nilai.index', [
            'kelas_id' => $request->kelas_id,
            'mapel_id' => $request->mapel_id,
            'semester' => $request->semester,
            'tahun_ajaran' => $request->tahun_ajaran
        ])->with('success', 'Data nilai berhasil disimpan.');
    }

    // Other standard resource methods...
    public function create() {}
    public function show(Nilai $nilai) {}
    public function edit(Nilai $nilai) {}
    public function update(Request $request, Nilai $nilai) {}
    public function destroy(Nilai $nilai) {}
}
