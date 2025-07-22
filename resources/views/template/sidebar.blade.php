<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <a href="index.html" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>DASHMIN</h3>
        </a>
        <div class="navbar-nav w-100">
            <a href="{{ route('dashboard.index') }}"
                class="nav-item nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}"><i
                    class="fa fa-home me-2"></i>Dashboard</a>
            {{-- <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                        class="fa fa-laptop me-2"></i>Elements</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="button.html" class="dropdown-item">Buttons</a>
                    <a href="typography.html" class="dropdown-item">Typography</a>
                    <a href="element.html" class="dropdown-item">Other Elements</a>
                </div>
            </div> --}}
            <a href="{{ route('kriteria.index') }}"
                class="nav-item nav-link {{ request()->routeIs('kriteria.*') ? 'active' : '' }}"><i
                    class="fa fa-clipboard me-2"></i>Kriteria</a>
            <a href="{{ route('penilaian.index') }}"
                class="nav-item nav-link {{ request()->routeIs('penilaian.*') ? 'active' : '' }}"><i
                    class="fa fa-calculator me-2"></i>Penilaian</a>
            <a href="{{ route('penyiar.index') }}"
                class="nav-item nav-link {{ request()->routeIs('penyiar.*') ? 'active' : '' }}"><i
                    class="fa fa-users me-2"></i>Penyiar</a>
            <a href="{{ route('hasil') }}"
                class="nav-item nav-link {{ request()->routeIs('hasil') ? 'active' : '' }}"><i
                    class="fa fa-trophy me-2"></i>Hasil</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-item nav-link"><i
                    class="fa fa-sign-out me-2"></i>Logout</button>
            </form>
        </div>
    </nav>
</div>
