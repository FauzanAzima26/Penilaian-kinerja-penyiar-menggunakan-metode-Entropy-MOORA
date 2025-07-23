<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <a href="index.html" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>DASHMIN</h3>
        </a>
        <div class="navbar-nav w-100">
            {{-- Menu Dashboard: Tersedia untuk semua role --}}
            <a href="{{ route('dashboard.index') }}"
                class="nav-item nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                <i class="fa fa-home me-2"></i>Dashboard
            </a>

            {{-- Menu khusus admin --}}
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('kriteria.index') }}"
                    class="nav-item nav-link {{ request()->routeIs('kriteria.*') ? 'active' : '' }}">
                    <i class="fa fa-clipboard me-2"></i>Kriteria
                </a>
                <a href="{{ route('penilaian.index') }}"
                    class="nav-item nav-link {{ request()->routeIs('penilaian.*') ? 'active' : '' }}">
                    <i class="fa fa-calculator me-2"></i>Penilaian
                </a>
                <a href="{{ route('penyiar.index') }}"
                    class="nav-item nav-link {{ request()->routeIs('penyiar.*') ? 'active' : '' }}">
                    <i class="fa fa-users me-2"></i>Penyiar
                </a>
                <a href="{{ route('hasil') }}"
                    class="nav-item nav-link {{ request()->routeIs('hasil') ? 'active' : '' }}">
                    <i class="fa fa-trophy me-2"></i>Hasil
                </a>

            {{-- Menu khusus evaluator --}}
            @elseif(Auth::user()->role === 'evaluator')
                <a href="{{ route('penilaian.index') }}"
                    class="nav-item nav-link {{ request()->routeIs('penilaian.*') ? 'active' : '' }}">
                    <i class="fa fa-calculator me-2"></i>Penilaian
                </a>
                <a href="{{ route('hasil') }}"
                    class="nav-item nav-link {{ request()->routeIs('hasil') ? 'active' : '' }}">
                    <i class="fa fa-trophy me-2"></i>Hasil
                </a>

            {{-- Menu khusus user --}}
            @elseif(Auth::user()->role === 'user')
                <a href="{{ route('hasil') }}"
                    class="nav-item nav-link {{ request()->routeIs('hasil') ? 'active' : '' }}">
                    <i class="fa fa-trophy me-2"></i>Hasil
                </a>
            @endif

            {{-- Logout untuk semua --}}
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            <a href="#" class="nav-item nav-link"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa fa-sign-out me-2"></i>Logout
            </a>
        </div>
    </nav>
</div>
