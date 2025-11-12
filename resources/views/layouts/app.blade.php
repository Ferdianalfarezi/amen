<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AMEN') }} - @yield('title')</title>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script>
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <!-- Favicon default (light mode) -->
    <link rel="icon" type="image/png" href="{{ asset('images/logofaviconblack.png') }}" media="(prefers-color-scheme: light)">

    <!-- Favicon untuk dark mode -->
    <link rel="icon" type="image/png" href="{{ asset('images/logofavicon.png') }}" media="(prefers-color-scheme: dark)">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            color: #000000;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 280px;
            background: #000000;
            color: #ffffff;
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 40;
            display: flex;
            flex-direction: column;
        }

        .sidebar.hidden {
            transform: translateX(-100%);
        }

        /* Struktur utama sidebar */
        .sidebar {
            width: 280px;
            height: 100vh; /* sidebar sepanjang layar */
            background-color: #121212;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            overflow-y: auto; /* biar bisa di-scroll kalau konten panjang */
            border-right: 1px solid #1f1f1f;
        }

        /* Header sidebar */
        .sidebar-header {
            padding: 2rem;
            border-bottom: 1px solid #1f1f1f;
            text-align: left; /* biar tengah */
            overflow: visible; /* pastikan isi gak kepotong */
            height: auto; /* biar menyesuaikan isi */
            flex-shrink: 0; /* biar gak ketarik kalau pakai flex */
        }

        /* Judul utama */
        .sidebar-header h1 {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin: 0;
        }

        /* Subjudul */
        .sidebar-header p {
            color: #888888;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-weight: 300;
            white-space: nowrap; /* biar bisa turun baris kalau sempit */
        }

        /* Isi sidebar */
        .sidebar-content {
            flex: 1;
            padding: 1rem 2rem;
        }

        .sidebar-content ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-content li {
            margin: 1rem 0;
            color: #ccc;
            cursor: pointer;
        }

        .sidebar-content li:hover {
            color: #fff;
        }


        .sidebar-nav {
            flex: 1;
            padding: 2rem 1rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            margin-bottom: 0.5rem;
            border-radius: 12px;
            color: #888888;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: #ffffff;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: -1;
        }

        .nav-item:hover {
            color: #ffffff;
            transform: translateX(4px);
        }

        .nav-item:hover::before {
            width: 100%;
        }

        .nav-item.active {
            background: #ffffff;
            color: #000000;
            font-weight: 600;
            transform: scale(1.02);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.1);
        }

        .nav-item svg {
            width: 20px;
            height: 20px;
            margin-right: 1rem;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item:hover svg {
            transform: scale(1.1);
        }

        .nav-item.active svg {
            animation: bounce 0.6s ease;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }

        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid #1f1f1f;
        }

        .user-profile {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: #0a0a0a;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .user-profile:hover {
            background: #1f1f1f;
            transform: translateY(-2px);
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #333333, #1a1a1a);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.875rem;
            margin-right: 1rem;
            border: 2px solid #2a2a2a;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .user-profile:hover .user-avatar {
            border-color: #ffffff;
            transform: rotate(5deg);
        }

        .user-info h4 {
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .user-info p {
            font-size: 0.75rem;
            color: #888888;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            transition: margin-left 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* Navbar */
        .navbar {
            position: sticky;
            top: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e5e5e5;
            padding: 1.25rem 2rem;
            z-index: 30;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .menu-toggle {
            width: 40px;
            height: 40px;
            border: none;
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .menu-toggle:hover {
            background: #000000;
            transform: rotate(90deg);
        }

        .menu-toggle:hover svg {
            stroke: #ffffff;
        }

        .menu-toggle svg {
            width: 24px;
            height: 24px;
            stroke: #000000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .divider {
            width: 1px;
            height: 32px;
            background: #e5e5e5;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #000000;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-badge {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .username {
            font-size: 0.875rem;
            font-weight: 500;
            color: #333333;
        }

        .role-badge {
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .role-badge.superadmin {
            background: #ff0000;
            color: #ffffff;
        }

        .role-badge.admin {
            background: #000000;
            color: #ffffff;
        }

        .role-badge.user {
            background: #f0f0f0;
            color: #000000;
        }
        

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            background: transparent;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            color: #666666;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .logout-btn:hover {
            background: #000000;
            color: #ffffff;
            transform: translateX(4px);
        }

        .logout-btn svg {
            width: 18px;
            height: 18px;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .logout-btn:hover svg {
            transform: translateX(4px);
        }

        /* Main Content Area */
        .content {
            padding: 2rem;
        }

        /* Notification */
        .notification {
            position: fixed;
            top: 6rem;
            right: 2rem;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            animation: slideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 50;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .notification.success {
            background: #000000;
            color: #ffffff;
        }

        .notification.error {
            background: #ffffff;
            color: #000000;
            border: 2px solid #000000;
        }

        .notification::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #ffffff;
            animation: pulse 1.5s infinite;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.5;
                transform: scale(1.2);
            }
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            padding: 2rem;
            border: 2px solid #f0f0f0;
            border-radius: 16px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #000000;
            transform: translateY(100%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 0;
        }

        .stat-card:hover {
            border-color: #000000;
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }

        .stat-card:hover::before {
            transform: translateY(0);
        }

        .stat-card-content {
            position: relative;
            z-index: 1;
            transition: color 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:hover .stat-card-content {
            color: #ffffff;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #888888;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .stat-card:hover .stat-label {
            color: #cccccc;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #000000;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:hover .stat-value {
            color: #ffffff;
            transform: scale(1.05);
        }

        /* Content Area */
        .content-box {
            padding: 3rem;
            border: 2px dashed #e5e5e5;
            border-radius: 16px;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .content-box:hover {
            border-color: #000000;
        }

        .content-placeholder {
            text-align: center;
        }

        .content-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: #000000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .content-box:hover .content-icon {
            transform: scale(1.1) rotate(12deg);
        }

        .content-icon svg {
            width: 40px;
            height: 40px;
            stroke: #ffffff;
        }

        .content-placeholder h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .content-placeholder p {
            color: #888888;
            max-width: 500px;
            margin: 0 auto;
        }

        /* Overlay for mobile */
        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 35;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <h1><i>AMEN</i></h1>
            <p>3D Drawing Management System</p>
        </div>
fvjnvenbe b vsfvbe
        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>

            <a href="{{ route('drawings.index') }}" class="nav-item {{ request()->routeIs('drawings.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                Drawing
            </a>

            
            @if(auth()->user()->hasPermission('user_management', 'view'))
                <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    User Management
                </a>
            @endif
            
        </nav>

        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar">{{ substr(auth()->user()->name, 0, 2) }}</div>
                <div class="user-info">
                    <h4>{{ auth()->user()->name }}</h4>
                    <p>{{ auth()->user()->departemen }}</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div id="mainContent" class="main-content">
        <!-- Navbar -->
        <nav class="navbar">
            <div class="navbar-left">
                <button id="menuToggle" class="menu-toggle">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="divider"></div>
                <h2 class="page-title">@yield('page-title', 'Dashboard')</h2>
            </div>

            <div class="navbar-right">
                <div class="user-badge">
                    <span class="username">{{ auth()->user()->username }}</span>
                    <span class="role-badge {{ auth()->user()->role }}">
                        {{ auth()->user()->role }}
                    </span>
                </div>
                <div class="divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </nav>

        

        <!-- Notifications -->
        @if (session('success'))
            <div class="notification success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="notification error">
                {{ session('error') }}
            </div>
        @endif

        <!-- Content -->
        <main class="content">
            @yield('content')
        </main>

        </div>
    </div>

    <footer style="background: #ffffff; border-top: 1px solid #e5e5e5; padding: 1.5rem 2rem; text-align: center; margin-left:220px; transition: all 0.3s ease;">
        <p style="margin: 0; font-size: 0.875rem; color: #888888;">
            &copy; {{ date('Y') }} <strong style="color: #000000;"><i>AMEN</i></strong> - 3D Drawing Management System. All rights reserved.
        </p>
    </footer>

    <!-- Overlay -->
    <div id="overlay" class="overlay"></div>

    <script>
        // ============================================
        // SIDEBAR & OVERLAY TOGGLE
        // ============================================
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const menuToggle = document.getElementById('menuToggle');
        const overlay = document.getElementById('overlay');

        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            sidebar.classList.toggle('hidden');
            mainContent.classList.toggle('expanded');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            sidebar.classList.add('hidden');
            mainContent.classList.add('expanded');
            overlay.classList.remove('active');
        });

        // ============================================
        // AUTO-HIDE NOTIFICATIONS
        // ============================================
        const notifications = document.querySelectorAll('.notification');
        notifications.forEach(notification => {
            setTimeout(() => {
                notification.style.animation = 'slideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) reverse';
                setTimeout(() => {
                    notification.remove();
                }, 400);
            }, 3000);
        });

        // ============================================
        // GLOBAL UPLOAD HANDLER dengan SweetAlert
        // ============================================
        window.handleFormUpload = function(formId, uploadUrl) {
            const form = document.getElementById(formId);
            if (!form) return;
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const submitBtn = this.querySelector('button[type="submit"]');
                const fileInput = this.querySelector('input[type="file"]');
                
                // Validasi file
                if (fileInput && !fileInput.files.length) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Please select a file first!',
                        confirmButtonColor: '#1F2937'
                    });
                    return;
                }
                
                // Show loading
                Swal.fire({
                    title: 'Uploading...',
                    html: 'Please wait while we upload your file',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                if (submitBtn) submitBtn.disabled = true;
                
                fetch(uploadUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    // Clone response untuk bisa read JSON
                    return response.json().then(data => {
                        return {
                            status: response.status,
                            ok: response.ok,
                            data: data
                        };
                    });
                })
                .then(({status, ok, data}) => {
                    Swal.close();
                    
                    // Handle berdasarkan response
                    if (ok && data.success) {
                        // SUCCESS
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message || 'File uploaded successfully!',
                            confirmButtonColor: '#1F2937',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        // ERROR (bisa 403, 500, atau success: false)
                        Swal.fire({
                            icon: 'error',
                            title: status === 403 ? 'Access Denied' : 'Upload Failed',
                            text: data.message || 'Something went wrong!',
                            confirmButtonColor: '#DC2626'
                        });
                    }
                })
                .catch(error => {
                    Swal.close();
                    console.error('Error:', error);
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Failed',
                        text: 'An error occurred while uploading the file.',
                        confirmButtonColor: '#DC2626'
                    });
                })
                .finally(() => {
                    if (submitBtn) submitBtn.disabled = false;
                });
            });
        };

        // ============================================
        // GLOBAL DELETE HANDLER dengan SweetAlert
        // ============================================
        window.handleDelete = function(deleteUrl, itemName = 'this item') {
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete ${itemName}. This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DC2626',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Deleting...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    fetch(deleteUrl, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        return response.json().then(data => {
                            return {
                                status: response.status,
                                ok: response.ok,
                                data: data
                            };
                        });
                    })
                    .then(({status, ok, data}) => {
                        Swal.close();
                        
                        if (ok && data.success) {
                            // SUCCESS
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: data.message || 'Item has been deleted.',
                                confirmButtonColor: '#1F2937',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            // ERROR
                            Swal.fire({
                                icon: 'error',
                                title: status === 403 ? 'Access Denied' : 'Delete Failed',
                                text: data.message || 'Something went wrong!',
                                confirmButtonColor: '#DC2626'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.close();
                        console.error('Error:', error);
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Delete Failed',
                            text: 'An error occurred while deleting the item.',
                            confirmButtonColor: '#DC2626'
                        });
                    });
                }
            });
        };

        // ============================================
        // FLASH MESSAGE HANDLER dengan SweetAlert
        // ============================================
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session("success") }}',
                confirmButtonColor: '#1F2937',
                timer: 2000,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session("error") }}',
                confirmButtonColor: '#DC2626'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: '<ul style="text-align: left; padding-left: 20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                confirmButtonColor: '#DC2626'
            });
        @endif
    </script>

    @stack('scripts')
</body>
</html>