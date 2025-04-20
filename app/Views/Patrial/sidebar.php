<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('/'); ?>">
        <div class="sidebar-brand-icon">
            <img src="<?= base_url('Assets/logo.png'); ?>" width="50" height="50" alt=""
                class="img-fluid bg-white rounded-circle">
        </div>
        <div class="sidebar-brand-text mx-2" max-width="50%">
            Analisis Barang
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">


    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?= ($active == 'Dashboard') ? 'active' : ''; ?>">
        <a class="nav-link" href="<?= base_url('/'); ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Admin
    </div>
    <li class="nav-item <?= ($active == 'Barang') ? 'active' : ''; ?>">
        <a class="nav-link collapsed " href="#" data-toggle="collapse" data-target="#menuBarang" aria-expanded="true"
            aria-controls="menuBarang">
            <i class="fas fa-fw fa-box"></i>
            <span>Barang</span>
        </a>
        <div id="menuBarang" class="collapse <?= ($active == 'Barang') ? 'show' : ''; ?>" aria-labelledby="headingPages"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Master Barang</h6>
                <a class="collapse-item <?= ($active == 'Barang') ? 'active' : ''; ?>"
                    href="<?= base_url('Barang'); ?>">Barang</a>
            </div>
        </div>
    </li>
    <li class="nav-item <?= ($active == 'Penjualan') ? 'active' : ''; ?>">
        <a class="nav-link collapsed " href="#" data-toggle="collapse" data-target="#menuPenjualan" aria-expanded="true"
            aria-controls="menuPenjualan">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Penjualan</span>
        </a>
        <div id="menuPenjualan" class="collapse <?= ($active == 'Penjualan') ? 'show' : ''; ?>"
            aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Master Penjualan</h6>
                <a class="collapse-item <?= ($active == 'Penjualan') ? 'active' : ''; ?>"
                    href="<?= base_url('Penjualan'); ?>">Penjualan</a>
            </div>
        </div>
    </li>
    <!-- <li class="nav-item <?= ($active == 'Users') ? 'active' : ''; ?>">
        <a class="nav-link collapsed " href="#" data-toggle="collapse" data-target="#uersMenu" aria-expanded="true"
            aria-controls="uersMenu">
            <i class="fas fa-fw fa-users"></i>
            <span>Users</span>
        </a>
        <div id="uersMenu" class="collapse <?= ($active == 'Users') ? 'show' : ''; ?>" aria-labelledby="headingPages"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Data User</h6>
                <a class="collapse-item <?= ($active == 'Users') ? 'active' : ''; ?>"
                    href="<?= base_url('Users'); ?>">User</a>
            </div>
        </div>
    </li> -->
    <li class="nav-item <?= ($active == 'Prediksi') ? 'active' : ''; ?>">
        <a class="nav-link collapsed " href="#" data-toggle="collapse" data-target="#menuPrediksi" aria-expanded="true"
            aria-controls="menuPrediksi">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Prediksi</span>
        </a>
        <div id="menuPrediksi" class="collapse <?= ($active == 'Prediksi') ? 'show' : ''; ?>"
            aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Prediksi</h6>
                <a class="collapse-item <?= ($active == 'Prediksi') ? 'active' : ''; ?>"
                    href="<?= base_url('Prediksi'); ?>">Prediksi Penjualan</a>
            </div>
        </div>
    </li>
    <!-- <li class="nav-item <?= ($active == 'Laporan') ? 'active' : ''; ?>">
        <a class="nav-link" href="<?= base_url('Laporan/Posyandu'); ?>">
            <i class=" fas fa-fw fa-chart-area"></i>
            <span>Laporan</span></a>
    </li> -->

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>