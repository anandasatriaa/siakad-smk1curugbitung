<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'nis', 'nama_siswa', 'kelas_id', 'jabatan', 'foto'])]
class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function nilais(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }
}
