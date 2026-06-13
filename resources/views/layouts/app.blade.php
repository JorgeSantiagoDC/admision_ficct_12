<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema de Admisión FICCT')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    @stack('styles')
    <style>
        .navbar-nav .nav-link.active {
            background-color: rgba(255,255,255,0.15);
            border-radius: 6px;
        }
        .navbar-nav .nav-link:hover {
            background-color: rgba(255,255,255,0.10);
            border-radius: 6px;
        }
        body {
            min-height: 100vh;
        }
    </style>
</head>
<body class="bg-light">

    @auth
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid">

            {{-- Logo --}}
            <a class="navbar-brand fw-bold" href="
                @if(Auth::user()->rol->nombre === 'Administrador') {{ route('admin.dashboard') }}
                @elseif(Auth::user()->rol->nombre === 'Docente') {{ route('docente.dashboard') }}
                @else {{ route('postulante.dashboard') }}
                @endif
            ">
                <i class="bi bi-mortarboard-fill me-2"></i>FICCT Admisión
            </a>

            {{-- Botón hamburguesa para móvil --}}
            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMenu">

                {{-- ── Menú Administrador ──────────────────────────── --}}
                @if(Auth::user()->rol->nombre === 'Administrador')
                <ul class="navbar-nav me-auto gap-1">

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                           href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.postulantes.*') ? 'active' : '' }}"
                           href="{{ route('admin.postulantes.index') }}">
                            <i class="bi bi-people-fill me-1"></i> Postulantes
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.grupos.*') ? 'active' : '' }}"
                           href="{{ route('admin.grupos.index') }}">
                            <i class="bi bi-person-badge-fill me-1"></i> Grupos
                        </a>
                    </li>

                    {{-- Menú desplegable para módulos pendientes --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#"
                           data-bs-toggle="dropdown">
                            <i class="bi bi-grid me-1"></i> Más módulos
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <span class="dropdown-item text-muted">
                                    <i class="bi bi-clock me-1"></i> Gestiones Académicas
                                    <span class="badge bg-secondary ms-1">Próximo</span>
                                </span>
                            </li>
                            <li>
                                <span class="dropdown-item text-muted">
                                    <i class="bi bi-book me-1"></i> Materias
                                    <span class="badge bg-secondary ms-1">Próximo</span>
                                </span>
                            </li>
                            <li>
                                <span class="dropdown-item text-muted">
                                    <i class="bi bi-person-workspace me-1"></i> Docentes
                                    <span class="badge bg-secondary ms-1">Próximo</span>
                                </span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <span class="dropdown-item text-muted">
                                    <i class="bi bi-file-earmark-check me-1"></i> Requisitos
                                    <span class="badge bg-secondary ms-1">Próximo</span>
                                </span>
                            </li>
                            <li>
                                <span class="dropdown-item text-muted">
                                    <i class="bi bi-credit-card me-1"></i> Pagos
                                    <span class="badge bg-secondary ms-1">Próximo</span>
                                </span>
                            </li>
                        </ul>
                    </li>

                </ul>
                @endif

                {{-- ── Menú Docente ─────────────────────────────────── --}}
                @if(Auth::user()->rol->nombre === 'Docente')
                <ul class="navbar-nav me-auto gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('docente.dashboard') ? 'active' : '' }}"
                           href="{{ route('docente.dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                </ul>
                @endif

                {{-- ── Menú Postulante ──────────────────────────────── --}}
                @if(Auth::user()->rol->nombre === 'Postulante')
                <ul class="navbar-nav me-auto gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('postulante.dashboard') ? 'active' : '' }}"
                           href="{{ route('postulante.dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                </ul>
                @endif

                {{-- ── Usuario + Logout ─────────────────────────────── --}}
                <ul class="navbar-nav ms-auto gap-2 align-items-center">
                    <li class="nav-item">
                        <span class="text-white">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ Auth::user()->usuario }}
                            <span class="badge bg-warning text-dark ms-1">
                                {{ Auth::user()->rol->nombre }}
                            </span>
                        </span>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-box-arrow-right me-1"></i> Cerrar Sesión
                            </button>
                        </form>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
    @endauth

    <main class="@auth container-fluid py-4 @endauth">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>