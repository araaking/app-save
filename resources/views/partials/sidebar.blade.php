<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>
        <div id="sidebar-menu">
            <div class="logo-box">
                <a href="{{ route('dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo.png') }}" 
                            alt="Logo Small" 
                            height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo.png') }}" 
                            alt="Logo Light" 
                            height="24">
                    </span>
                </a>
                <a href="{{ route('dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo.png') }}" 
                            alt="Logo Small" 
                            height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo.png') }}" 
                            alt="Logo Dark" 
                            height="24">
                    </span>
                </a>
            </div>

            <ul id="side-menu">
                <!-- Menu Utama -->
                <li class="menu-title">Menu</li>
                <li>
                    <a href="{{ route('dashboard') }}" class="tp-link">
                        <i data-feather="home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Menu Akademik -->
                <li class="menu-title">Akademik</li>
                <li>
                    <a href="{{ route('tahun-ajaran.index') }}" class="tp-link">
                        <i data-feather="calendar"></i>
                        <span>Tahun Ajaran</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('kelas.index') }}" class="tp-link">
                        <i data-feather="grid"></i>
                        <span>Kelas</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('siswa.index') }}" class="tp-link">
                        <i data-feather="user"></i>
                        <span>Siswa</span>
                    </a>
                </li>

                <!-- Menu Keuangan -->
                <li class="menu-title">Tabungan</li>
                <li>
                    <a href="{{ route('transaksi.index') }}" class="tp-link">
                        <i data-feather="credit-card"></i>
                        <span>Data Tabungan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('transaksi.create') }}" class="tp-link">
                        <i data-feather="save"></i>
                        <span>Tabungan/Cicilan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('buku-tabungan.index') }}" class="tp-link">
                        <i data-feather="book"></i>
                        <span>Buku Tabungan</span>
                    </a>
                </li>

                <!-- Tambahan Menu Biaya Sekolah -->
                

                <li class="menu-title">Data Penarikan</li>
                <li>
                    <a href="{{ route('penarikan.index') }}" class="tp-link">
                        <i data-feather="list"></i>
                        <span>Daftar Penarikan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('penarikan.create') }}" class="tp-link">
                        <i data-feather="download"></i>
                        <span>Tambah Penarikan</span>
                    </a>
                </li>
                
                <li class="menu-title">Keuangan Sekolah</li>
                <li>
                    <a href="{{ route('biaya-sekolah.index') }}" class="tp-link">
                        <i data-feather="credit-card"></i>
                        <span>Biaya Sekolah</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('tagihan.index') }}" class="tp-link">
                        <i data-feather="file-text"></i>
                        <span>Tagihan Siswa</span>
                    </a>
                </li>
            </ul>
            
            <div class="clearfix"></div>
        </div>
    </div>
</div>
