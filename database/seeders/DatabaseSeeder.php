<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use App\Models\JadwalPelajaran;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users
        $superadmin = User::create([
            'name' => 'Superadmin',
            'email' => 'superadmin@siakad.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);

        $admin = User::create([
            'name' => 'Admin Sekolah',
            'email' => 'admin@siakad.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $guruUser = User::create([
            'name' => 'Budi Santoso, S.Kom',
            'email' => 'guru@siakad.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);

        $siswaUser = User::create([
            'name' => 'Ahmad Reza',
            'email' => 'siswa@siakad.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);

        // 2. Seed Guru
        $guru = Guru::create([
            'user_id' => $guruUser->id,
            'nip' => '198001012005011002',
            'nama_guru' => 'Budi Santoso, S.Kom',
            'no_telp' => '081234567890',
        ]);

        $guru2 = Guru::create([
            'user_id' => null,
            'nip' => '198501012010012003',
            'nama_guru' => 'Siti Aminah, M.Pd',
            'no_telp' => '081298765432',
        ]);

        // 3. Seed Kelas
        $kelas10RPL = Kelas::create([
            'nama_kelas' => '10 Rekayasa Perangkat Lunak 1',
            'wali_kelas_id' => $guru->id,
        ]);

        $kelas10TKJ = Kelas::create([
            'nama_kelas' => '10 Teknik Komputer Jaringan 1',
            'wali_kelas_id' => $guru2->id,
        ]);

        // 4. Seed Siswa
        $siswa = Siswa::create([
            'user_id' => $siswaUser->id,
            'nis' => '202410001',
            'nama_siswa' => 'Ahmad Reza',
            'kelas_id' => $kelas10RPL->id,
            'jabatan' => 'sekretaris', // Bisa input absensi
        ]);

        Siswa::create([
            'user_id' => null,
            'nis' => '202410002',
            'nama_siswa' => 'Dina Mariana',
            'kelas_id' => $kelas10RPL->id,
            'jabatan' => null,
        ]);

        // 5. Seed Mata Pelajaran
        $mapelWeb = MataPelajaran::create([
            'kode_mapel' => 'MP-WEB',
            'nama_mapel' => 'Pemrograman Web dan Perangkat Bergerak',
        ]);

        $mapelMat = MataPelajaran::create([
            'kode_mapel' => 'MP-MTK',
            'nama_mapel' => 'Matematika Terapan',
        ]);

        // 6. Seed Jadwal Pelajaran
        JadwalPelajaran::create([
            'kelas_id' => $kelas10RPL->id,
            'mapel_id' => $mapelWeb->id,
            'guru_id' => $guru->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        JadwalPelajaran::create([
            'kelas_id' => $kelas10RPL->id,
            'mapel_id' => $mapelMat->id,
            'guru_id' => $guru2->id,
            'hari' => 'Selasa',
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
        ]);
        
        $this->command->info('Default SIAKAD data seeded successfully!');
    }
}
