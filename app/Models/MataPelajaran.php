<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['kode_mapel', 'nama_mapel'])]
class MataPelajaran extends Model
{
    use HasFactory;

    public function jadwalPelajaran(): HasMany
    {
        return $this->hasMany(JadwalPelajaran::class, 'mapel_id');
    }

    public function nilais(): HasMany
    {
        return $this->hasMany(Nilai::class, 'mapel_id');
    }
}
