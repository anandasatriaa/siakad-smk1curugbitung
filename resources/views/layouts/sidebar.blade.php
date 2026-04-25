        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo" style="height: 75px;"> <a href="{{ route('dashboard') }}" class="app-brand-link">
                    <span class="app-brand-logo demo">
                        <img src="{{ asset('assets/img/logo-smkn1curugbitung.png') }}" alt="Logo" width="40"
                            height="40"
                            style="object-fit: contain; filter: drop-shadow(0px 1px 2px rgba(0,0,0,0.1));">
                    </span>
                    <span class="app-brand-text demo menu-text fw-bolder ms-2"
                        style="text-transform: none; line-height: 1.2; font-size: 1.1rem; letter-spacing: 0.5px;">
                        SMKN 1<br>
                        <small style="font-size: 0.8rem; font-weight: 600; opacity: 0.8;">CURUGBITUNG</small>
                    </span>
                </a>

                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                    <i class="bx bx-chevron-left bx-sm align-middle"></i>
                </a>
            </div>

            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-1">
                <!-- Dashboard -->
                <li class="menu-item {{ Route::is('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-home-circle"></i>
                        <div data-i18n="Analytics">Dashboard</div>
                    </a>
                </li>

                @auth
                    @if (auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin')
                        <li class="menu-header small text-uppercase">
                            <span class="menu-header-text">Master Data</span>
                        </li>
                        <li class="menu-item {{ Route::is('admin.guru.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.guru.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-user-pin"></i>
                                <div data-i18n="Guru">Data Guru</div>
                            </a>
                        </li>
                        <li class="menu-item {{ Route::is('admin.siswa.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.siswa.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-user"></i>
                                <div data-i18n="Siswa">Data Siswa</div>
                            </a>
                        </li>
                        <li class="menu-item {{ Route::is('admin.kelas.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.kelas.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-buildings"></i>
                                <div data-i18n="Kelas">Data Kelas</div>
                            </a>
                        </li>
                        <li class="menu-item {{ Route::is('admin.mapel.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.mapel.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-book"></i>
                                <div data-i18n="Mata Pelajaran">Mata Pelajaran</div>
                            </a>
                        </li>
                        <li class="menu-item {{ Route::is('admin.jadwal.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.jadwal.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-calendar"></i>
                                <div data-i18n="Jadwal Pelajaran">Jadwal Pelajaran</div>
                            </a>
                        </li>
                        <li class="menu-header small text-uppercase">
                            <span class="menu-header-text">Akademik & Laporan</span>
                        </li>
                        <li class="menu-item {{ Route::is('nilai.*') ? 'active' : '' }}">
                            <a href="{{ route('nilai.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-spreadsheet"></i>
                                <div data-i18n="Input Nilai">Input Nilai</div>
                            </a>
                        </li>
                        <li class="menu-item {{ Route::is('admin.laporan.nilai') ? 'active' : '' }}">
                            <a href="{{ route('admin.laporan.nilai') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                                <div data-i18n="Laporan Nilai">Laporan Nilai</div>
                            </a>
                        </li>
                        <li class="menu-item {{ Route::is('admin.laporan.absensi') ? 'active' : '' }}">
                            <a href="{{ route('admin.laporan.absensi') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-detail"></i>
                                <div data-i18n="Absensi">Melihat Absensi</div>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->role === 'superadmin' || auth()->user()->role === 'guru')
                        <li class="menu-header small text-uppercase">
                            <span class="menu-header-text">Guru</span>
                        </li>
                        <li class="menu-item {{ Route::is('guru.jadwal.mengajar') ? 'active' : '' }}">
                            <a href="{{ route('guru.jadwal.mengajar') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-calendar-event"></i>
                                <div data-i18n="Jadwal Mengajar">Jadwal Mengajar</div>
                            </a>
                        </li>
                        <li class="menu-item {{ Route::is('nilai.*') ? 'active' : '' }}">
                            <a href="{{ route('nilai.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-spreadsheet"></i>
                                <div data-i18n="Input Nilai">Input Nilai Siswa</div>
                            </a>
                        </li>
                        <li class="menu-item {{ Route::is('guru.siswa.kelas') ? 'active' : '' }}">
                            <a href="{{ route('guru.siswa.kelas') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-group"></i>
                                <div data-i18n="Data Siswa">Data Siswa per Kelas</div>
                            </a>
                        </li>
                        <li class="menu-item {{ Route::is('guru.absensi.riwayat') ? 'active' : '' }}">
                            <a href="{{ route('guru.absensi.riwayat') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-history"></i>
                                <div data-i18n="Riwayat Absensi">Riwayat Absensi Siswa</div>
                            </a>
                        </li>
                        <li class="menu-item {{ Route::is('guru.laporan.*') ? 'active' : '' }}">
                            <a href="{{ route('guru.laporan.export') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-export"></i>
                                <div data-i18n="Export Laporan">Export Laporan</div>
                            </a>
                        </li>
                    @endif

                    @php
                        $isSekretaris = false;
                        if (auth()->user()->role === 'siswa') {
                            $siswa = \App\Models\Siswa::where('user_id', auth()->user()->id)->first();
                            if ($siswa && strtolower($siswa->jabatan) === 'sekretaris') {
                                $isSekretaris = true;
                            }
                        }
                    @endphp

                    @if (auth()->user()->role === 'superadmin' || $isSekretaris)
                        <li class="menu-header small text-uppercase">
                            <span class="menu-header-text">Siswa / Sekretaris</span>
                        </li>
                        <li class="menu-item {{ Route::is('siswa.absensi.*') ? 'active' : '' }}">
                            <a href="{{ route('siswa.absensi.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-check-square"></i>
                                <div data-i18n="Absensi">Input Absensi Kelas</div>
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>
        </aside>
        <!-- / Menu -->
