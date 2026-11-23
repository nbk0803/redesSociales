<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link d-flex align-items-center">
        <img src="{{ asset('backend/dist/img/redes-sociales.svg') }}"
             alt="Redes Sociales"
             class="brand-image"
             style="width: 33px; height: 33px; margin-left: .5rem; opacity: .95;">
        <span class="brand-text font-weight-light">Redes Sociales</span>
    </a>

    <div class="sidebar">
        @auth
            <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
                <div class="image">
                    <img src="{{ Auth::user()->photo_url }}"
                         class="img-circle elevation-2" alt="{{ Auth::user()->name }}" style="width: 34px; height: 34px; object-fit: cover;">
                </div>
                <div class="info">
                    <span class="d-block text-white">{{ Auth::user()->name }}</span>
                </div>
            </div>
        @endif

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('groups.index') }}" class="nav-link {{ request()->routeIs('groups.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Grupos</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('memberships.index') }}" class="nav-link {{ request()->routeIs('memberships.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-id-card"></i>
                        <p>Membresías</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-friends"></i>
                        <p>Usuarios</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
