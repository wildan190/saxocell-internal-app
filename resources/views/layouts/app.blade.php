<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=rubik:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div style="display: flex; align-items: center;">
                <div class="sidebar-logo">
                    <i data-feather="grid"></i>
                </div>
                <span class="sidebar-brand">{{ config('app.name', 'Laravel') }}</span>
            </div>
            <button class="sidebar-toggle" id="sidebarToggle">
                <i data-feather="x"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Main</div>
                <div class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i data-feather="home" class="nav-icon"></i>
                        Dashboard
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <i data-feather="package" class="nav-icon"></i>
                        Products
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                        <i data-feather="users" class="nav-icon"></i>
                        Suppliers
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('inventory.index') }}" class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                        <i data-feather="clipboard" class="nav-icon"></i>
                        Inventory
                    </a>
                </div>
                
                <div class="nav-section-title">Procurement</div>
                <div class="nav-item">
                    <a href="{{ route('purchase-orders.index') }}" class="nav-link {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}">
                        <i data-feather="shopping-bag" class="nav-icon"></i>
                        Purchase Orders
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('delivery-orders.index') }}" class="nav-link {{ request()->routeIs('delivery-orders.*') ? 'active' : '' }}">
                        <i data-feather="package" class="nav-icon"></i>
                        Delivery Orders
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('invoices.index') }}" class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                        <i data-feather="file-text" class="nav-icon"></i>
                        Invoices
                    </a>
                </div>

                <div class="nav-section-title">Warehouse Management</div>
                <div class="nav-item">
                    <a href="{{ route('warehouses.index') }}" class="nav-link {{ request()->routeIs('warehouses.*') ? 'active' : '' }}">
                        <i data-feather="archive" class="nav-icon"></i>
                        Warehouses
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('stores.index') }}" class="nav-link {{ request()->routeIs('stores.*') ? 'active' : '' }}">
                        <i data-feather="shopping-cart" class="nav-icon"></i>
                        Stores
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('stock-transfers.create-request') }}" class="nav-link {{ request()->routeIs('stock-transfers.create-request') ? 'active' : '' }}">
                        <i data-feather="download" class="nav-icon"></i>
                        Request Stock
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('stock-transfers.index') }}" class="nav-link {{ (request()->routeIs('stock-transfers.*') && !request()->routeIs('stock-transfers.create-request')) ? 'active' : '' }}">
                        <i data-feather="truck" class="nav-icon"></i>
                        Stock Transfers
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('stock-opnames.index') }}" class="nav-link {{ request()->routeIs('stock-opnames.*') ? 'active' : '' }}">
                        <i data-feather="check-square" class="nav-icon"></i>
                        Stock Opname
                    </a>
                </div>
                <div class="nav-section-title">Finance & Accounting</div>
                <div class="nav-item">
                    <a href="{{ route('finance.index') }}" class="nav-link {{ request()->routeIs('finance.index') ? 'active' : '' }}">
                        <i data-feather="monitor" class="nav-icon"></i>
                        Finance Dashboard
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('finance.accounts.index') }}" class="nav-link {{ request()->routeIs('finance.accounts.*') ? 'active' : '' }}">
                        <i data-feather="list" class="nav-icon"></i>
                        Chart of Accounts
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('finance.journals.index') }}" class="nav-link {{ request()->routeIs('finance.journals.*') ? 'active' : '' }}">
                        <i data-feather="book-open" class="nav-icon"></i>
                        General Ledger
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('finance.payables') }}" class="nav-link {{ request()->routeIs('finance.payables') ? 'active' : '' }}">
                        <i data-feather="external-link" class="nav-icon"></i>
                        Accounts Payable
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('finance.reconciliations.index') }}" class="nav-link {{ request()->routeIs('finance.reconciliations.*') ? 'active' : '' }}">
                        <i data-feather="refresh-cw" class="nav-icon"></i>
                        Bank Reconciliation
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('finance.reports') }}" class="nav-link {{ request()->routeIs('finance.reports.*') ? 'active' : '' }}">
                        <i data-feather="pie-chart" class="nav-icon"></i>
                        Reports
                    </a>
                </div>

                <div class="nav-section-title">HR & Personnel</div>
                <div class="nav-item">
                    <a href="{{ route('hrm.employees.index') }}" class="nav-link {{ request()->routeIs('hrm.employees.*') ? 'active' : '' }}">
                        <i data-feather="users" class="nav-icon"></i>
                        Employees
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('hrm.attendance.index') }}" class="nav-link {{ request()->routeIs('hrm.attendance.*') ? 'active' : '' }}">
                        <i data-feather="clock" class="nav-icon"></i>
                        Attendance
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('hrm.overtime.index') }}" class="nav-link {{ request()->routeIs('hrm.overtime.*') ? 'active' : '' }}">
                        <i data-feather="watch" class="nav-icon"></i>
                        Overtime
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('hrm.payroll.index') }}" class="nav-link {{ request()->routeIs('hrm.payroll.*') || request()->routeIs('hrm.salary-components.*') ? 'active' : '' }}">
                        <i data-feather="dollar-sign" class="nav-icon"></i>
                        Payroll
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('hrm.recruitment.index') }}" class="nav-link {{ request()->routeIs('hrm.recruitment.*') || request()->routeIs('hrm.applicants.*') || request()->routeIs('hrm.jobs.*') ? 'active' : '' }}">
                        <i data-feather="user-plus" class="nav-icon"></i>
                        Recruitment
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('hrm.kpi.index') }}" class="nav-link {{ request()->routeIs('hrm.kpi.*') ? 'active' : '' }}">
                        <i data-feather="trending-up" class="nav-icon"></i>
                        KPI Evaluation
                    </a>
                </div>

                <div class="nav-section-title">My Portal (ESS)</div>
                <div class="nav-item">
                    <a href="{{ route('hrm.ess.index') }}" class="nav-link {{ request()->routeIs('hrm.ess.index') ? 'active' : '' }}">
                        <i data-feather="home" class="nav-icon"></i>
                        My Dashboard
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('hrm.ess.attendance') }}" class="nav-link {{ request()->routeIs('hrm.ess.attendance') ? 'active' : '' }}">
                        <i data-feather="calendar" class="nav-icon"></i>
                        My Attendance
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('hrm.ess.payslips') }}" class="nav-link {{ request()->routeIs('hrm.ess.payslips') ? 'active' : '' }}">
                        <i data-feather="file-text" class="nav-icon"></i>
                        My Payslips
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ route('hrm.ess.profile') }}" class="nav-link {{ request()->routeIs('hrm.ess.profile') ? 'active' : '' }}">
                        <i data-feather="user" class="nav-icon"></i>
                        My Profile
                    </a>
                </div>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <header class="topbar">
            <button class="menu-toggle" id="menuToggle">
                <i data-feather="menu"></i>
            </button>
            <button class="sidebar-open-toggle" id="sidebarOpenToggle" style="display: none;">
                <i data-feather="sidebar"></i>
            </button>
            <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
            <div class="user-menu">
                <div class="user-avatar" style="background: linear-gradient(135deg, #64748b 0%, #475569 100%);">
                    <i data-feather="user" class="w-5 h-5 text-white/80"></i>
                </div>
            </div>
        </header>

        @yield('breadcrumb')

        <div id="ajaxContent">
            @yield('content')
            <div id="ajaxScripts">
                @stack('scripts')
            </div>
        </div>

        <!-- AJAX Skeleton Loader (Hidden) -->
        <div id="pageSkeleton" class="hidden content-wrapper animate-pulse">
            <div class="skeleton-title skeleton-box"></div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                @for($i=0; $i<4; $i++)
                <div class="h-32 skeleton-box"></div>
                @endfor
            </div>
            <div class="space-y-4">
                @for($i=0; $i<10; $i++)
                <div class="h-12 skeleton-box"></div>
                @endfor
            </div>
        </div>
    </div>

    <script>
        // Initialize sidebar functionality immediately
        (function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const overlay = document.getElementById('overlay');
            const menuToggle = document.getElementById('menuToggle');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOpenToggle = document.getElementById('sidebarOpenToggle');

            // Cache DOM queries for better performance
            let isInitialized = false;

            function toggleSidebar() {
                if (!isInitialized) return;

                const isCollapsed = sidebar.classList.contains('collapsed');

                if (isCollapsed) {
                    // Opening sidebar
                    sidebar.classList.remove('collapsed');
                    overlay.classList.remove('active');
                    if (window.innerWidth > 1024) {
                        mainContent.classList.remove('expanded');
                    }
                    menuToggle.style.display = 'none';
                    sidebarOpenToggle.style.display = 'none';
                } else {
                    // Closing sidebar - show menu toggle for closing, hide sidebar open toggle
                    sidebar.classList.add('collapsed');
                    if (window.innerWidth <= 1024) {
                        overlay.classList.add('active');
                    }
                    if (window.innerWidth > 1024) {
                        mainContent.classList.add('expanded');
                    }
                    menuToggle.style.display = 'flex';
                    sidebarOpenToggle.style.display = 'none';
                }
            }

            function openSidebar() {
                if (!isInitialized) return;

                sidebar.classList.remove('collapsed');
                overlay.classList.remove('active');
                if (window.innerWidth > 1024) {
                    mainContent.classList.remove('expanded');
                }
                menuToggle.style.display = 'none';
                sidebarOpenToggle.style.display = 'none';
            }

            // Add event listeners
            menuToggle.addEventListener('click', toggleSidebar);
            sidebarToggle.addEventListener('click', toggleSidebar);
            sidebarOpenToggle.addEventListener('click', openSidebar);
            overlay.addEventListener('click', toggleSidebar);

            // Handle responsive behavior with debouncing
            let resizeTimeout;
            function handleResize() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    if (window.innerWidth <= 1024) {
                        sidebar.classList.add('collapsed');
                        overlay.classList.add('active');
                        menuToggle.style.display = 'flex';
                        sidebarOpenToggle.style.display = 'none';
                    } else {
                        sidebar.classList.remove('collapsed');
                        overlay.classList.remove('active');
                        mainContent.classList.remove('expanded');
                        menuToggle.style.display = 'none';
                        sidebarOpenToggle.style.display = 'none';
                    }
                }, 100);
            }

            window.addEventListener('resize', handleResize);

            // Initial setup
            handleResize();
            isInitialized = true;

            // Sidebar Scroll Preservation
            const sidebarScrollKey = 'sidebar_scroll_pos';
            sidebar.addEventListener('scroll', () => {
                localStorage.setItem(sidebarScrollKey, sidebar.scrollTop);
            });
            const savedScroll = localStorage.getItem(sidebarScrollKey);
            if (savedScroll) {
                sidebar.scrollTop = savedScroll;
            }

            // AJAX Navigation System
            const ajaxContent = document.getElementById('ajaxContent');
            const pageSkeleton = document.getElementById('pageSkeleton');

            function loadPage(url, push = true) {
                pageSkeleton.classList.remove('hidden');
                ajaxContent.classList.add('hidden');
                
                fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Extract pieces
                    const newContent = doc.querySelector('#ajaxContent');
                    const newTitle = doc.querySelector('title').innerText;
                    const topbarTitle = doc.querySelector('.topbar-title')?.innerText || '';
                    
                    // Update DOM
                    ajaxContent.innerHTML = newContent.innerHTML;
                    document.title = newTitle;
                    if (document.querySelector('.topbar-title')) {
                        document.querySelector('.topbar-title').innerText = topbarTitle;
                    }

                    // Re-execute scripts
                    const scripts = newContent.querySelectorAll('script');
                    scripts.forEach(oldScript => {
                        const newScript = document.createElement('script');
                        Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                        newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                        document.body.appendChild(newScript);
                        // Optional: remove them after execution if you want to keep DOM clean
                        // setTimeout(() => newScript.remove(), 100); 
                    });

                    // Re-initialize Feather icons and scripts
                    if (window.feather) window.feather.replace();
                    
                    // Trigger DOMContentLoaded for page-specific scripts
                    document.dispatchEvent(new Event('DOMContentLoaded'));
                    
                    // Handle history
                    if (push) history.pushState({ url }, newTitle, url);
                    
                    // Scroll to top of content
                    window.scrollTo(0, 0);

                    // Re-mark active nav
                    updateActiveNav(url);
                    
                    pageSkeleton.classList.add('hidden');
                    ajaxContent.classList.remove('hidden');
                })
                .catch(err => {
                    console.error('AJAX Load failed:', err);
                    window.location.href = url; // Fallback to normal load
                });
            }

            function updateActiveNav(url) {
                const links = document.querySelectorAll('.nav-link');
                links.forEach(link => {
                    if (link.href === url || url.startsWith(link.href)) {
                        link.classList.add('active');
                    } else {
                        link.classList.remove('active');
                    }
                });
            }

            // Intercept Link Clicks
            document.addEventListener('click', e => {
                const link = e.target.closest('a');
                if (!link) return;
                
                const url = link.href;
                const isLocal = url.startsWith(window.location.origin);
                const isFile = url.includes('/storage/') || url.includes('/download/');
                
                if (isLocal && !isFile && !link.hasAttribute('data-no-ajax') && !link.target) {
                    e.preventDefault();
                    loadPage(url);
                }
            });

            // Handle Back/Forward
            window.addEventListener('popstate', e => {
                if (e.state && e.state.url) {
                    loadPage(e.state.url, false);
                }
            });

            // Intercept Form Submissions
            document.addEventListener('submit', e => {
                const form = e.target;
                if (form.hasAttribute('data-no-ajax') || form.target) return;
                
                e.preventDefault();
                const formData = new FormData(form);
                const url = form.action;
                const method = form.method.toUpperCase();

                pageSkeleton.classList.remove('hidden');
                ajaxContent.classList.add('hidden');

                fetch(url, {
                    method: method === 'GET' ? 'GET' : 'POST',
                    body: method === 'GET' ? null : formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => {
                    if (response.redirected) {
                        loadPage(response.url);
                        return;
                    }
                    return response.text();
                })
                .then(html => {
                    if (!html) return;
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    const newContent = doc.querySelector('#ajaxContent');
                    const newTitle = doc.querySelector('title').innerText;
                    const topbarTitle = doc.querySelector('.topbar-title')?.innerText || '';
                    
                    ajaxContent.innerHTML = newContent.innerHTML;
                    document.title = newTitle;
                    if (document.querySelector('.topbar-title')) {
                        document.querySelector('.topbar-title').innerText = topbarTitle;
                    }

                    // Re-execute scripts
                    const scripts = newContent.querySelectorAll('script');
                    scripts.forEach(oldScript => {
                        const newScript = document.createElement('script');
                        Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                        newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                        document.body.appendChild(newScript);
                    });

                    if (window.feather) window.feather.replace();
                    
                    // Trigger DOMContentLoaded for page-specific scripts
                    document.dispatchEvent(new Event('DOMContentLoaded'));

                    window.scrollTo(0, 0);
                    
                    pageSkeleton.classList.add('hidden');
                    ajaxContent.classList.remove('hidden');
                })
                .catch(err => {
                    console.error('Form AJAX failed:', err);
                    form.submit(); // Fallback
                });
            });

            // Dropdown functionality (remained same)
            document.addEventListener('click', function(e) {
                const toggle = e.target.closest('.dropdown-toggle');
                const menu = e.target.closest('.dropdown-menu');

                // If clicking a toggle
                if (toggle) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const currentMenu = toggle.nextElementSibling;
                    const isHidden = currentMenu.classList.contains('hidden');

                    // Close all other dropdowns
                    document.querySelectorAll('.dropdown-menu').forEach(m => {
                        if (m !== currentMenu) m.classList.add('hidden');
                    });

                    // Toggle current
                    if (isHidden) {
                        currentMenu.classList.remove('hidden');
                    } else {
                        currentMenu.classList.add('hidden');
                    }
                } 
                // If clicking outside
                else if (!menu) {
                    document.querySelectorAll('.dropdown-menu').forEach(m => {
                        m.classList.add('hidden');
                    });
                }
            });
        })();

    </script>

    <!-- Scripts stack moved inside ajaxContent for SPA behavior -->
</body>
</html>
