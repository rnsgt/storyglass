<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Dashboard' }} | StoryGlass Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(135deg, #558b8b 0%, #84a9ac 100%);
            padding: 20px 0;
            transition: all 0.3s;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar .logo {
            text-align: center;
            padding: 20px 0;
            color: white;
            font-size: 24px;
            font-weight: bold;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }

        .sidebar .nav-item {
            padding: 15px 25px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }

        .sidebar .nav-item:hover,
        .sidebar .nav-item.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
            border-left-color: white;
        }

        .sidebar .nav-item i {
            margin-right: 15px;
            font-size: 18px;
            width: 25px;
        }

        /* Navbar Admin */
        .admin-navbar {
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            height: 70px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            z-index: 999;
        }

        .admin-navbar h4 {
            margin: 0;
            color: #333;
            font-weight: 600;
        }

        .admin-navbar .user-menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .admin-navbar .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-navbar .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #558b8b 0%, #84a9ac 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            margin-top: 70px;
            padding: 30px;
            min-height: calc(100vh - 70px);
        }

        /* Cards */
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .stat-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stat-card.primary .icon {
            background: linear-gradient(135deg, #558b8b 0%, #84a9ac 100%);
            color: white;
        }

        .stat-card.success .icon {
            background: linear-gradient(135deg, #3cb371 0%, #5fcc8b 100%);
            color: white;
        }

        .stat-card.warning .icon {
            background: linear-gradient(135deg, #ff9a56 0%, #ff6a88 100%);
            color: white;
        }

        .stat-card.info .icon {
            background: linear-gradient(135deg, #6dd5ed 0%, #2193b0 100%);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                padding: 0;
            }
            
            .admin-navbar,
            .main-content {
                margin-left: 0;
                left: 0;
            }
        }

        /* Dropdown */
        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Table */
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        /* Custom Button Colors */
        .btn-primary {
            background: linear-gradient(135deg, #558b8b 0%, #84a9ac 100%);
            border: none;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #467373 0%, #6d8b8e 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(85, 139, 139, 0.3);
        }

        .btn-outline-primary {
            color: #558b8b;
            border-color: #558b8b;
        }

        .btn-outline-primary:hover {
            background-color: #558b8b;
            border-color: #558b8b;
        }

        /* Badge Colors */
        .badge.bg-success {
            background: linear-gradient(135deg, #3cb371 0%, #5fcc8b 100%) !important;
        }

        .badge.bg-warning {
            background: linear-gradient(135deg, #ff9a56 0%, #ff6a88 100%) !important;
        }

        .badge.bg-info {
            background: linear-gradient(135deg, #6dd5ed 0%, #2193b0 100%) !important;
        }

        /* Table Hover */
        .table-hover tbody tr:hover {
            background-color: rgba(132, 169, 172, 0.1);
        }

        /* Status Badge Styling */
        .status-pending {
            background: linear-gradient(135deg, #ff9a56 0%, #ff6a88 100%);
            color: white;
        }

        .status-processing {
            background: linear-gradient(135deg, #6dd5ed 0%, #2193b0 100%);
            color: white;
        }

        .status-shipped {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .status-completed {
            background: linear-gradient(135deg, #3cb371 0%, #5fcc8b 100%);
            color: white;
        }

        .status-cancelled {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
        }
    </style>
    {{ $styles ?? '' }}
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <i class="bi bi-eyeglasses"></i> StoryGlass
        </div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.products.list') }}" class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i>
            <span>Produk</span>
        </a>
        <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="bi bi-bag-check"></i>
            <span>Pesanan</span>
        </a>
        <a href="{{ route('admin.customers.index') }}" class="nav-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            <span>Pelanggan</span>
        </a>
        <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <i class="bi bi-graph-up"></i>
            <span>Laporan</span>
        </a>
        <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i>
            <span>Pengaturan</span>
        </a>
        <hr style="border-color: rgba(255,255,255,0.1); margin: 20px 0;">
        <a href="{{ route('home') }}" class="nav-item">
            <i class="bi bi-globe"></i>
            <span>Lihat Website</span>
        </a>
    </div>

    <!-- Navbar -->
    <nav class="admin-navbar">
        <h4>{{ $pageTitle ?? 'Dashboard' }}</h4>
        <div class="user-menu">
            <div class="dropdown">
                <a class="user-info dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" style="text-decoration: none; color: #333;">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span>{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Pengaturan</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a class="dropdown-item" href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{ $slot }}
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    {{ $scripts ?? '' }}
</body>
</html>
