@props(['title' => null])

@php
    // Get unread notifications for the current user
    $unreadNotifications = 0;
    $recentNotifications = collect();
    
    if (auth()->check()) {
        $unreadNotifications = auth()->user()->unreadNotifications()->count();
        $recentNotifications = auth()->user()->notifications()
            ->latest()
            ->limit(5)
            ->get();
    }
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: false }" 
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" 
      :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'MineCraft Hosting' }} - Professional Server Management</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
        
        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <!-- jQuery (required for DataTables) -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        
        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
        
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
                --gradient-success: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
                --gradient-warning: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
                --gradient-danger: linear-gradient(135deg, #ff6b6b 0%, #feca57 100%);
                
                --glass-bg: rgba(255, 255, 255, 0.1);
                --glass-border: rgba(255, 255, 255, 0.2);
                --glass-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                
                --sidebar-width: 280px;
                --header-height: 80px;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                color: #1f2937;
                overflow-x: hidden;
            }

            .dark body {
                background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
                color: #f8fafc;
            }

            /* Modern Layout Grid */
            .app-layout {
                display: grid;
                grid-template-areas: 
                    "sidebar header"
                    "sidebar main";
                grid-template-columns: var(--sidebar-width) 1fr;
                grid-template-rows: var(--header-height) 1fr;
                min-height: 100vh;
                transition: all 0.3s ease;
            }

            .app-layout.sidebar-collapsed {
                grid-template-columns: 80px 1fr;
            }

            @media (max-width: 1024px) {
                .app-layout {
                    grid-template-areas: 
                        "header header"
                        "main main";
                    grid-template-columns: 1fr;
                    grid-template-rows: var(--header-height) 1fr;
                }
            }

            /* Modern Sidebar */
            .sidebar {
                grid-area: sidebar;
                background: rgba(15, 23, 42, 0.95);
                backdrop-filter: blur(20px);
                border-right: 1px solid rgba(255, 255, 255, 0.1);
                overflow-y: auto;
                transition: all 0.3s ease;
                z-index: 1000;
            }

            @media (max-width: 1024px) {
                .sidebar {
                    position: fixed;
                    top: 0;
                    left: 0;
                    height: 100vh;
                    transform: translateX(-100%);
                    z-index: 1001;
                }

                .sidebar.open {
                    transform: translateX(0);
                }
            }

            .sidebar-brand {
                padding: 2rem;
                display: flex;
                align-items: center;
                gap: 1rem;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .sidebar-brand-icon {
                width: 50px;
                height: 50px;
                background: var(--gradient-primary);
                border-radius: 15px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.5rem;
                font-weight: bold;
                box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            }

            .sidebar-brand-text h2 {
                color: white;
                font-size: 1.25rem;
                font-weight: 800;
                margin-bottom: 0.25rem;
            }

            .sidebar-brand-text p {
                color: rgba(255, 255, 255, 0.7);
                font-size: 0.75rem;
                text-transform: uppercase;
                letter-spacing: 0.1em;
            }

            .sidebar-nav {
                padding: 1.5rem 1rem;
            }

            .sidebar-section {
                margin-bottom: 2rem;
            }

            .sidebar-section-title {
                color: rgba(255, 255, 255, 0.5);
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                margin-bottom: 1rem;
                padding: 0 1rem;
            }

            .sidebar-menu {
                list-style: none;
            }

            .sidebar-menu-item {
                margin-bottom: 0.5rem;
            }

            .sidebar-menu-link {
                display: flex;
                align-items: center;
                gap: 1rem;
                padding: 0.875rem 1rem;
                color: rgba(255, 255, 255, 0.8);
                text-decoration: none;
                border-radius: 12px;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .sidebar-menu-link::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: var(--gradient-primary);
                opacity: 0;
                transition: opacity 0.3s ease;
                z-index: -1;
            }

            .sidebar-menu-link:hover::before {
                opacity: 0.1;
            }

            .sidebar-menu-link.active::before {
                opacity: 0.2;
            }

            .sidebar-menu-link:hover {
                color: white;
                transform: translateX(4px);
            }

            .sidebar-menu-link.active {
                color: #60a5fa;
                background: rgba(96, 165, 250, 0.1);
            }

            .sidebar-menu-icon {
                width: 20px;
                text-align: center;
                font-size: 1.1rem;
            }

            .sidebar-menu-text {
                font-weight: 500;
                font-size: 0.875rem;
            }

            .sidebar-menu-badge {
                margin-left: auto;
                background: var(--gradient-danger);
                color: white;
                font-size: 0.75rem;
                font-weight: 600;
                padding: 0.25rem 0.5rem;
                border-radius: 10px;
                min-width: 20px;
                text-align: center;
            }

            /* Modern Header */
            .header {
                grid-area: header;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 2rem;
                z-index: 999;
            }

            .dark .header {
                background: rgba(15, 23, 42, 0.95);
                border-bottom-color: rgba(255, 255, 255, 0.1);
            }

            .header-left {
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .sidebar-toggle {
                display: none;
                background: none;
                border: none;
                color: #6b7280;
                font-size: 1.25rem;
                cursor: pointer;
                padding: 0.5rem;
                border-radius: 8px;
                transition: all 0.3s ease;
            }

            .sidebar-toggle:hover {
                background: rgba(107, 114, 128, 0.1);
                color: #374151;
            }

            .dark .sidebar-toggle {
                color: #9ca3af;
            }

            .dark .sidebar-toggle:hover {
                background: rgba(156, 163, 175, 0.1);
                color: #d1d5db;
            }

            @media (max-width: 1024px) {
                .sidebar-toggle {
                    display: block;
                }
            }

            .breadcrumb {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                color: #6b7280;
                font-size: 0.875rem;
            }

            .breadcrumb a {
                color: #3b82f6;
                text-decoration: none;
                transition: color 0.3s ease;
            }

            .breadcrumb a:hover {
                color: #1d4ed8;
            }

            .breadcrumb-separator {
                color: #9ca3af;
            }

            .header-right {
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .header-actions {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .header-action {
                position: relative;
                background: none;
                border: none;
                color: #6b7280;
                font-size: 1.1rem;
                cursor: pointer;
                padding: 0.75rem;
                border-radius: 12px;
                transition: all 0.3s ease;
            }

            .header-action:hover {
                background: rgba(107, 114, 128, 0.1);
                color: #374151;
                transform: translateY(-1px);
            }

            .dark .header-action {
                color: #9ca3af;
            }

            .dark .header-action:hover {
                background: rgba(156, 163, 175, 0.1);
                color: #d1d5db;
            }

            .notification-badge {
                position: absolute;
                top: 0.5rem;
                right: 0.5rem;
                background: var(--gradient-danger);
                color: white;
                font-size: 0.75rem;
                font-weight: 600;
                padding: 0.125rem 0.375rem;
                border-radius: 10px;
                min-width: 18px;
                height: 18px;
                display: flex;
                align-items: center;
                justify-content: center;
                animation: pulse 2s infinite;
            }

            @keyframes pulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.1); }
            }

            .user-menu {
                position: relative;
            }

            .user-avatar {
                width: 40px;
                height: 40px;
                background: var(--gradient-primary);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .user-avatar:hover {
                transform: scale(1.05);
                box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            }

            /* Main Content */
            .main-content {
                grid-area: main;
                overflow-y: auto;
                background: rgba(255, 255, 255, 0.05);
            }

            .content-wrapper {
                padding: 2rem;
                max-width: 1400px;
                margin: 0 auto;
            }

            /* Modern Cards */
            .modern-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 20px;
                padding: 2rem;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                margin-bottom: 1.5rem;
            }

            .modern-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            }

            .dark .modern-card {
                background: rgba(30, 41, 59, 0.95);
                border-color: rgba(255, 255, 255, 0.1);
            }

            .card-header {
                display: flex;
                align-items: center;
                justify-content: between;
                margin-bottom: 1.5rem;
                padding-bottom: 1rem;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            }

            .dark .card-header {
                border-bottom-color: rgba(255, 255, 255, 0.1);
            }

            .card-title {
                font-size: 1.25rem;
                font-weight: 700;
                color: #1f2937;
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .dark .card-title {
                color: #f8fafc;
            }

            .card-icon {
                width: 40px;
                height: 40px;
                background: var(--gradient-primary);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.1rem;
            }

            /* Stats Grid */
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1.5rem;
                margin-bottom: 2rem;
            }

            .stat-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                border-radius: 16px;
                padding: 1.5rem;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .stat-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 4px;
                background: var(--gradient-primary);
            }

            .stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            }

            .dark .stat-card {
                background: rgba(30, 41, 59, 0.95);
                border-color: rgba(255, 255, 255, 0.1);
            }

            .stat-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 1rem;
            }

            .stat-title {
                color: #6b7280;
                font-size: 0.875rem;
                font-weight: 500;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            .dark .stat-title {
                color: #9ca3af;
            }

            .stat-icon {
                width: 35px;
                height: 35px;
                background: var(--gradient-primary);
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1rem;
            }

            .stat-value {
                font-size: 2rem;
                font-weight: 800;
                color: #1f2937;
                margin-bottom: 0.5rem;
            }

            .dark .stat-value {
                color: #f8fafc;
            }

            .stat-change {
                display: flex;
                align-items: center;
                gap: 0.25rem;
                font-size: 0.75rem;
                font-weight: 500;
            }

            .stat-change.positive {
                color: #10b981;
            }

            .stat-change.negative {
                color: #ef4444;
            }

            /* Modern Buttons */
            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                padding: 0.75rem 1.5rem;
                font-weight: 600;
                font-size: 0.875rem;
                border-radius: 12px;
                border: none;
                cursor: pointer;
                text-decoration: none;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .btn::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transition: left 0.5s ease;
            }

            .btn:hover::before {
                left: 100%;
            }

            .btn-primary {
                background: var(--gradient-primary);
                color: white;
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
                color: white;
                text-decoration: none;
            }

            .btn-secondary {
                background: rgba(107, 114, 128, 0.1);
                color: #374151;
                border: 1px solid rgba(107, 114, 128, 0.2);
            }

            .btn-secondary:hover {
                background: rgba(107, 114, 128, 0.2);
                transform: translateY(-1px);
                color: #374151;
                text-decoration: none;
            }

            .dark .btn-secondary {
                background: rgba(156, 163, 175, 0.1);
                color: #d1d5db;
                border-color: rgba(156, 163, 175, 0.2);
            }

            .dark .btn-secondary:hover {
                background: rgba(156, 163, 175, 0.2);
                color: #d1d5db;
            }

            .btn-success {
                background: var(--gradient-success);
                color: white;
                box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4);
            }

            .btn-success:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(79, 172, 254, 0.6);
                color: white;
                text-decoration: none;
            }

            .btn-warning {
                background: var(--gradient-warning);
                color: white;
                box-shadow: 0 4px 15px rgba(250, 112, 154, 0.4);
            }

            .btn-warning:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(250, 112, 154, 0.6);
                color: white;
                text-decoration: none;
            }

            .btn-danger {
                background: var(--gradient-danger);
                color: white;
                box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
            }

            .btn-danger:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(255, 107, 107, 0.6);
                color: white;
                text-decoration: none;
            }

            /* Dark Mode Toggle */
            .dark-mode-toggle {
                background: none;
                border: none;
                color: #6b7280;
                font-size: 1.1rem;
                cursor: pointer;
                padding: 0.75rem;
                border-radius: 12px;
                transition: all 0.3s ease;
            }

            .dark-mode-toggle:hover {
                background: rgba(107, 114, 128, 0.1);
                color: #374151;
                transform: scale(1.1);
            }

            .dark .dark-mode-toggle {
                color: #fbbf24;
            }

            .dark .dark-mode-toggle:hover {
                background: rgba(251, 191, 36, 0.1);
                color: #f59e0b;
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .content-wrapper {
                    padding: 1rem;
                }

                .stats-grid {
                    grid-template-columns: 1fr;
                }

                .modern-card {
                    padding: 1.5rem;
                }

                .card-header {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 1rem;
                }
            }

            /* Scrollbar Styling */
            ::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }
            
            ::-webkit-scrollbar-track {
                background: rgba(0, 0, 0, 0.1);
                border-radius: 4px;
            }
            
            ::-webkit-scrollbar-thumb {
                background: var(--gradient-primary);
                border-radius: 4px;
            }
            
            ::-webkit-scrollbar-thumb:hover {
                background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
            }

            /* Overlay for mobile sidebar */
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1000;
            }

            @media (max-width: 1024px) {
                .sidebar-overlay.active {
                    display: block;
                }
            }

            /* Enhanced DataTables Styling */
            .dataTables_wrapper {
                margin-top: 1rem;
            }

            .dataTables_filter {
                margin-bottom: 1.5rem;
            }

            .dataTables_filter input {
                background: rgba(255, 255, 255, 0.9);
                border: 2px solid rgba(0, 0, 0, 0.1);
                border-radius: 12px;
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
                transition: all 0.3s ease;
                width: 300px;
            }

            .dataTables_filter input:focus {
                outline: none;
                border-color: #60a5fa;
                box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
            }

            .dark .dataTables_filter input {
                background: rgba(30, 41, 59, 0.9);
                border-color: rgba(255, 255, 255, 0.1);
                color: #f8fafc;
            }

            .dataTables_length select {
                background: rgba(255, 255, 255, 0.9);
                border: 2px solid rgba(0, 0, 0, 0.1);
                border-radius: 8px;
                padding: 0.5rem;
                margin: 0 0.5rem;
            }

            .dark .dataTables_length select {
                background: rgba(30, 41, 59, 0.9);
                border-color: rgba(255, 255, 255, 0.1);
                color: #f8fafc;
            }

            .dataTable {
                background: rgba(255, 255, 255, 0.95);
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                border: none;
                width: 100% !important;
            }

            .dark .dataTable {
                background: rgba(30, 41, 59, 0.95);
            }

            .dataTable thead {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }

            .dataTable thead th {
                color: white;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                font-size: 0.75rem;
                padding: 1.25rem 1rem;
                border: none;
                position: relative;
            }

            .dataTable thead th:hover {
                background: rgba(255, 255, 255, 0.1);
            }

            .dataTable tbody tr {
                transition: all 0.3s ease;
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            }

            .dataTable tbody tr:hover {
                background: rgba(102, 126, 234, 0.05);
                transform: scale(1.01);
            }

            .dark .dataTable tbody tr {
                border-bottom-color: rgba(255, 255, 255, 0.05);
            }

            .dark .dataTable tbody tr:hover {
                background: rgba(96, 165, 250, 0.1);
            }

            .dataTable tbody td {
                padding: 1rem;
                vertical-align: middle;
                border: none;
                font-size: 0.875rem;
            }

            .dataTable tbody td:first-child {
                font-weight: 600;
                color: #1f2937;
            }

            .dark .dataTable tbody td:first-child {
                color: #f8fafc;
            }

            .dataTables_info, .dataTables_paginate {
                margin-top: 1.5rem;
                color: #6b7280;
                font-size: 0.875rem;
            }

            .dark .dataTables_info, .dark .dataTables_paginate {
                color: #9ca3af;
            }

            .dataTables_paginate .paginate_button {
                background: rgba(255, 255, 255, 0.9);
                border: 1px solid rgba(0, 0, 0, 0.1);
                border-radius: 8px;
                padding: 0.5rem 0.75rem;
                margin: 0 0.25rem;
                color: #374151;
                text-decoration: none;
                transition: all 0.3s ease;
                display: inline-block;
            }

            .dataTables_paginate .paginate_button:hover {
                background: var(--gradient-primary);
                color: white;
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            }

            .dataTables_paginate .paginate_button.current {
                background: var(--gradient-primary);
                color: white;
                box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            }

            .dark .dataTables_paginate .paginate_button {
                background: rgba(30, 41, 59, 0.9);
                border-color: rgba(255, 255, 255, 0.1);
                color: #d1d5db;
            }

            /* Status Badges */
            .status-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.375rem 0.75rem;
                border-radius: 20px;
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            .status-active {
                background: rgba(34, 197, 94, 0.1);
                color: #059669;
                border: 1px solid rgba(34, 197, 94, 0.2);
            }

            .status-inactive {
                background: rgba(239, 68, 68, 0.1);
                color: #dc2626;
                border: 1px solid rgba(239, 68, 68, 0.2);
            }

            .status-pending {
                background: rgba(245, 158, 11, 0.1);
                color: #d97706;
                border: 1px solid rgba(245, 158, 11, 0.2);
            }

            .status-completed {
                background: rgba(34, 197, 94, 0.1);
                color: #059669;
                border: 1px solid rgba(34, 197, 94, 0.2);
            }

            .status-cancelled {
                background: rgba(239, 68, 68, 0.1);
                color: #dc2626;
                border: 1px solid rgba(239, 68, 68, 0.2);
            }

            /* Modal Styling */
            .modal-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.75);
                backdrop-filter: blur(8px);
                z-index: 1050;
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }

            .modal-backdrop.show {
                opacity: 1;
                visibility: visible;
            }

            .modal-content {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(20px);
                border-radius: 20px;
                border: 1px solid rgba(255, 255, 255, 0.2);
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                max-width: 600px;
                width: 90%;
                max-height: 90vh;
                overflow-y: auto;
                transform: scale(0.9) translateY(20px);
                transition: all 0.3s ease;
            }

            .modal-backdrop.show .modal-content {
                transform: scale(1) translateY(0);
            }

            .dark .modal-content {
                background: rgba(15, 23, 42, 0.98);
                border-color: rgba(255, 255, 255, 0.1);
            }

            .modal-header {
                padding: 2rem 2rem 1rem;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .dark .modal-header {
                border-bottom-color: rgba(255, 255, 255, 0.1);
            }

            .modal-title {
                font-size: 1.5rem;
                font-weight: 700;
                color: #1f2937;
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .dark .modal-title {
                color: #f8fafc;
            }

            .modal-close {
                background: none;
                border: none;
                color: #6b7280;
                font-size: 1.5rem;
                cursor: pointer;
                padding: 0.5rem;
                border-radius: 8px;
                transition: all 0.3s ease;
            }

            .modal-close:hover {
                background: rgba(107, 114, 128, 0.1);
                color: #374151;
            }

            .dark .modal-close {
                color: #9ca3af;
            }

            .dark .modal-close:hover {
                background: rgba(156, 163, 175, 0.1);
                color: #d1d5db;
            }

            .modal-body {
                padding: 2rem;
            }

            .modal-footer {
                padding: 1rem 2rem 2rem;
                display: flex;
                gap: 1rem;
                justify-content: flex-end;
                border-top: 1px solid rgba(0, 0, 0, 0.1);
            }

            .dark .modal-footer {
                border-top-color: rgba(255, 255, 255, 0.1);
            }

            /* Form Styling */
            .form-group {
                margin-bottom: 1.5rem;
            }

            .form-label {
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 600;
                color: #374151;
                font-size: 0.875rem;
            }

            .dark .form-label {
                color: #d1d5db;
            }

            .form-input, .form-select, .form-textarea {
                width: 100%;
                background: rgba(255, 255, 255, 0.9);
                border: 2px solid rgba(0, 0, 0, 0.1);
                border-radius: 12px;
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
                transition: all 0.3s ease;
                color: #1f2937;
            }

            .form-input:focus, .form-select:focus, .form-textarea:focus {
                outline: none;
                border-color: #60a5fa;
                box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
                background: rgba(255, 255, 255, 1);
            }

            .dark .form-input, .dark .form-select, .dark .form-textarea {
                background: rgba(30, 41, 59, 0.9);
                border-color: rgba(255, 255, 255, 0.1);
                color: #f8fafc;
            }

            .dark .form-input:focus, .dark .form-select:focus, .dark .form-textarea:focus {
                background: rgba(30, 41, 59, 1);
                border-color: #60a5fa;
            }

            .form-textarea {
                min-height: 100px;
                resize: vertical;
            }

            .form-checkbox {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                margin-bottom: 1rem;
            }

            .form-checkbox input[type="checkbox"] {
                width: 18px;
                height: 18px;
                accent-color: #60a5fa;
            }

            .form-error {
                margin-top: 0.5rem;
                color: #dc2626;
                font-size: 0.875rem;
                font-weight: 500;
            }

            .form-help {
                margin-top: 0.5rem;
                color: #6b7280;
                font-size: 0.875rem;
            }

            .dark .form-help {
                color: #9ca3af;
            }

            /* Action Buttons */
            .action-buttons {
                display: flex;
                gap: 0.5rem;
                align-items: center;
            }

            .action-btn {
                padding: 0.5rem;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                transition: all 0.3s ease;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 36px;
                height: 36px;
                font-size: 0.875rem;
            }

            .action-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }

            .action-btn-view {
                background: rgba(59, 130, 246, 0.1);
                color: #3b82f6;
                border: 1px solid rgba(59, 130, 246, 0.2);
            }

            .action-btn-edit {
                background: rgba(245, 158, 11, 0.1);
                color: #f59e0b;
                border: 1px solid rgba(245, 158, 11, 0.2);
            }

            .action-btn-delete {
                background: rgba(239, 68, 68, 0.1);
                color: #ef4444;
                border: 1px solid rgba(239, 68, 68, 0.2);
            }

            .action-btn-status {
                background: rgba(34, 197, 94, 0.1);
                color: #22c55e;
                border: 1px solid rgba(34, 197, 94, 0.2);
            }

            /* Loading States */
            .loading {
                position: relative;
                color: transparent !important;
            }

            .loading::after {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 16px;
                height: 16px;
                margin: -8px 0 0 -8px;
                border: 2px solid transparent;
                border-top: 2px solid currentColor;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            /* Responsive Improvements */
            @media (max-width: 768px) {
                .dataTables_filter input {
                    width: 100%;
                }
                
                .modal-content {
                    width: 95%;
                    margin: 1rem;
                }
                
                .modal-header, .modal-body, .modal-footer {
                    padding: 1rem;
                }
                
                .action-buttons {
                    flex-direction: column;
                    gap: 0.25rem;
                }
            }

            /* Enhanced Form Styling for Better Text Visibility */
            .form-control {
                color: #374151 !important;
                background-color: #ffffff !important;
                border: 1px solid #d1d5db !important;
                border-radius: 0.375rem;
                padding: 0.75rem;
                font-size: 0.875rem;
                transition: all 0.2s ease;
            }

            .form-control:focus {
                outline: none;
                border-color: #3b82f6 !important;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
                background-color: #ffffff !important;
                color: #374151 !important;
            }

            .form-control option {
                color: #374151 !important;
                background-color: #ffffff !important;
            }

            .form-group label {
                color: #374151 !important;
                font-weight: 500 !important;
                font-size: 0.875rem;
                margin-bottom: 0.5rem;
                display: block;
            }

            .dark .form-control {
                color: #f9fafb !important;
                background-color: #374151 !important;
                border-color: #4b5563 !important;
            }

            .dark .form-control:focus {
                background-color: #374151 !important;
                color: #f9fafb !important;
                border-color: #60a5fa !important;
            }

            .dark .form-control option {
                color: #f9fafb !important;
                background-color: #374151 !important;
            }

            .dark .form-group label {
                color: #f9fafb !important;
            }

            .modal-title {
                color: #374151 !important;
                font-weight: 600;
            }

            .dark .modal-title {
                color: #f9fafb !important;
            }

            .form-help {
                color: #6b7280 !important;
                font-size: 0.75rem;
                margin-top: 0.25rem;
            }

            .dark .form-help {
                color: #9ca3af !important;
            }
        </style>
    </head>

    <body>
        <div class="app-layout" :class="{ 'sidebar-collapsed': false }">
            <!-- Sidebar -->
            <aside class="sidebar" :class="{ 'open': sidebarOpen }">
                <!-- Brand -->
                <div class="sidebar-brand">
                    <div class="sidebar-brand-icon">
                        <i class="fas fa-server"></i>
                    </div>
                    <div class="sidebar-brand-text">
                        <h2>MineCraft Hosting</h2>
                        <p>Professional Management</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="sidebar-nav">
                    @if(Auth::check() && Auth::user()->hasRole('admin'))
                        <!-- Admin Navigation -->
                        <div class="sidebar-section">
                            <h3 class="sidebar-section-title">Administration</h3>
                            <ul class="sidebar-menu">
                                <li class="sidebar-menu-item">
                                    <a href="{{ url('/dashboard') }}" class="sidebar-menu-link {{ request()->is('dashboard') ? 'active' : '' }}">
                                        <i class="sidebar-menu-icon fas fa-chart-line"></i>
                                        <span class="sidebar-menu-text">Dashboard</span>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item">
                                    <a href="{{ route('admin.users') }}" class="sidebar-menu-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                                        <i class="sidebar-menu-icon fas fa-users"></i>
                                        <span class="sidebar-menu-text">Users</span>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item">
                                    <a href="{{ route('admin.plans.index') }}" class="sidebar-menu-link {{ request()->is('admin/plans*') ? 'active' : '' }}">
                                        <i class="sidebar-menu-icon fas fa-box"></i>
                                        <span class="sidebar-menu-text">Plans</span>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item">
                                    <a href="{{ route('admin.orders.index') }}" class="sidebar-menu-link {{ request()->is('admin/orders*') ? 'active' : '' }}">
                                        <i class="sidebar-menu-icon fas fa-shopping-cart"></i>
                                        <span class="sidebar-menu-text">Orders</span>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item">
                                    <a href="{{ url('/admin/manage/servers') }}" class="sidebar-menu-link {{ request()->is('admin/manage/servers*') ? 'active' : '' }}">
                                        <i class="sidebar-menu-icon fas fa-server"></i>
                                        <span class="sidebar-menu-text">Servers</span>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item">
                                    <a href="{{ url('/admin/manage/billing') }}" class="sidebar-menu-link {{ request()->is('admin/manage/billing*') ? 'active' : '' }}">
                                        <i class="sidebar-menu-icon fas fa-credit-card"></i>
                                        <span class="sidebar-menu-text">Billing</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="sidebar-section">
                            <h3 class="sidebar-section-title">System</h3>
                            <ul class="sidebar-menu">
                                <li class="sidebar-menu-item">
                                    <a href="{{ url('/admin/analytics') }}" class="sidebar-menu-link {{ request()->is('admin/analytics*') ? 'active' : '' }}">
                                        <i class="sidebar-menu-icon fas fa-chart-bar"></i>
                                        <span class="sidebar-menu-text">Analytics</span>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item">
                                    <a href="{{ url('/admin/notifications') }}" class="sidebar-menu-link {{ request()->is('admin/notifications*') ? 'active' : '' }}">
                                        <i class="sidebar-menu-icon fas fa-bell"></i>
                                        <span class="sidebar-menu-text">Notifications</span>
                                        @if($unreadNotifications > 0)
                                            <span class="sidebar-menu-badge">{{ $unreadNotifications > 9 ? '9+' : $unreadNotifications }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="sidebar-menu-item">
                                    <a href="{{ url('/admin/settings') }}" class="sidebar-menu-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                                        <i class="sidebar-menu-icon fas fa-cog"></i>
                                        <span class="sidebar-menu-text">Settings</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @elseif(Auth::check() && Auth::user()->hasRole('reseller'))
                        <!-- Reseller Navigation -->
                        <div class="sidebar-section">
                            <h3 class="sidebar-section-title">Reseller Panel</h3>
                            <ul class="sidebar-menu">
                                <li class="sidebar-menu-item">
                                    <a href="{{ url('/reseller/dashboard') }}" class="sidebar-menu-link {{ request()->is('reseller/dashboard') ? 'active' : '' }}">
                                        <i class="sidebar-menu-icon fas fa-chart-line"></i>
                                        <span class="sidebar-menu-text">Dashboard</span>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item">
                                    <a href="{{ url('/reseller/customers') }}" class="sidebar-menu-link {{ request()->is('reseller/customers*') ? 'active' : '' }}">
                                        <i class="sidebar-menu-icon fas fa-users"></i>
                                        <span class="sidebar-menu-text">Customers</span>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item">
                                    <a href="{{ url('/reseller/servers') }}" class="sidebar-menu-link {{ request()->is('reseller/servers*') ? 'active' : '' }}">
                                        <i class="sidebar-menu-icon fas fa-server"></i>
                                        <span class="sidebar-menu-text">Servers</span>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item">
                                    <a href="{{ url('/reseller/billing') }}" class="sidebar-menu-link {{ request()->is('reseller/billing*') ? 'active' : '' }}">
                                        <i class="sidebar-menu-icon fas fa-credit-card"></i>
                                        <span class="sidebar-menu-text">Billing</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @else
                        <!-- Customer Navigation -->
                        <div class="sidebar-section">
                            <h3 class="sidebar-section-title">My Account</h3>
                            <ul class="sidebar-menu">
                                <li class="sidebar-menu-item">
                                    <a href="{{ url('/dashboard') }}" class="sidebar-menu-link {{ request()->is('dashboard') ? 'active' : '' }}">
                                        <i class="sidebar-menu-icon fas fa-chart-line"></i>
                                        <span class="sidebar-menu-text">Dashboard</span>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item">
                                    <a href="{{ url('/servers') }}" class="sidebar-menu-link {{ request()->is('servers*') ? 'active' : '' }}">
                                        <i class="sidebar-menu-icon fas fa-server"></i>
                                        <span class="sidebar-menu-text">My Servers</span>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item">
                                    <a href="{{ url('/billing') }}" class="sidebar-menu-link {{ request()->is('billing*') ? 'active' : '' }}">
                                        <i class="sidebar-menu-icon fas fa-credit-card"></i>
                                        <span class="sidebar-menu-text">Billing</span>
                                    </a>
                                </li>
                                <li class="sidebar-menu-item">
                                    <a href="{{ url('/support') }}" class="sidebar-menu-link {{ request()->is('support*') ? 'active' : '' }}">
                                        <i class="sidebar-menu-icon fas fa-life-ring"></i>
                                        <span class="sidebar-menu-text">Support</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif

                    <!-- Common Navigation -->
                    @auth
                    <div class="sidebar-section">
                        <h3 class="sidebar-section-title">Account</h3>
                        <ul class="sidebar-menu">
                            <li class="sidebar-menu-item">
                                <a href="{{ route('profile.edit') }}" class="sidebar-menu-link {{ request()->is('profile*') ? 'active' : '' }}">
                                    <i class="sidebar-menu-icon fas fa-user"></i>
                                    <span class="sidebar-menu-text">Profile</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="sidebar-menu-link w-full text-left">
                                        <i class="sidebar-menu-icon fas fa-sign-out-alt"></i>
                                        <span class="sidebar-menu-text">Logout</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endauth
                </nav>
            </aside>

            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <button @click="sidebarOpen = !sidebarOpen" class="sidebar-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="breadcrumb">
                        <a href="{{ url('/dashboard') }}">Home</a>
                        <span class="breadcrumb-separator">/</span>
                        <span>{{ $title ?? 'Dashboard' }}</span>
                    </div>
                </div>

                <div class="header-right">
                    <div class="header-actions">
                        <!-- Dark Mode Toggle -->
                        <button @click="darkMode = !darkMode" class="dark-mode-toggle">
                            <i :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'"></i>
                        </button>

                        @auth
                        <!-- Notifications -->
                        <div class="header-action" x-data="{ open: false }">
                            <button @click="open = !open" class="header-action">
                                <i class="fas fa-bell"></i>
                                @if($unreadNotifications > 0)
                                    <span class="notification-badge">{{ $unreadNotifications > 9 ? '9+' : $unreadNotifications }}</span>
                                @endif
                            </button>
                        </div>

                        <!-- User Menu -->
                        <div class="user-menu" x-data="{ open: false }">
                            <button @click="open = !open" class="user-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </button>
                        </div>
                        @endauth
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="main-content">
                <div class="content-wrapper">
                    {{ $slot }}
                </div>
            </main>
        </div>

        <!-- Mobile Sidebar Overlay -->
        <div class="sidebar-overlay" :class="{ 'active': sidebarOpen }" @click="sidebarOpen = false"></div>

        <!-- Alpine.js Initialization -->
        <script>
            // Initialize tooltips, dropdowns, etc.
            document.addEventListener('alpine:init', () => {
                Alpine.store('theme', {
                    dark: localStorage.getItem('darkMode') === 'true',
                    toggle() {
                        this.dark = !this.dark;
                        localStorage.setItem('darkMode', this.dark);
                    }
                });
            });
        </script>

        <!-- Custom Scripts Stack -->
        @stack('scripts')
    </body>
</html>
