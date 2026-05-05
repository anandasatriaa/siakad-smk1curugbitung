<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodeAkademik extends Model
{
    use HasFactory;

    protected $table = 'periode_akademik';

    protected $fillable = ['tahun_ajaran', 'semester', 'is_aktif'];

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class, 'periode_id');
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class, 'periode_id');
    }

    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class, 'periode_id');
    }

    public function riwayatKelas(): HasMany
    {
        return $this->hasMany(RiwayatKelas::class, 'periode_id');
    }
}
