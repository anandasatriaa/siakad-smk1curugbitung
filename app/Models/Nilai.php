<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['siswa_id', 'mapel_id', 'periode_id', 'nilai_tugas', 'nilai_uts', 'nilai_uas', 'nilai_akhir', 'capaian_kompetensi'])]
class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeAkademik::class, 'periode_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }
}
