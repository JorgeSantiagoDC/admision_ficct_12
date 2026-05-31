@extends('layouts.app')

@section('title', 'Iniciar Sesión — FICCT')

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #1a3c6e 0%, #2d6fd4 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .login-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    .login-header {
        background: linear-gradient(135deg, #1a3c6e, #2d6fd4);
        border-radius: 16px 16px 0 0;
        padding: 2rem;
        text-align: center;
    }
    .btn-login {
        background: linear-gradient(135deg, #1a3c6e, #2d6fd4);
        border: none;
        border-radius: 8px;
        padding: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .btn-login:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        transition: all 0.2s;
    }
    .form-control:focus {
        border-color: #2d6fd4;
        box-shadow: 0 0 0 0.2rem rgba(45,111,212,0.25);
    }
</style>
@endpush

@section('content')
<div style="min-height:100vh; display:flex; align-items:center; justify-content:center;">
    <div style="width: 100%; max-width: 420px; padding: 1rem;">

        {{-- Card principal --}}
        <div class="card login-card">

            {{-- Cabecera institucional --}}
            <div class="login-header">
                <i class="bi bi-mortarboard-fill text-white" style="font-size: 3rem;"></i>
                <h4 class="text-white fw-bold mt-2 mb-0">Sistema de Admisión</h4>
                <p class="text-white-50 mb-0" style="font-size: 0.85rem;">
                    Facultad de Ingeniería en Ciencias de la Computación y Telecomunicaciones
                </p>
            </div>

            {{-- Cuerpo del formulario --}}
            <div class="card-body p-4">

                {{-- Mensaje de éxito (logout) --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Errores generales --}}
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    {{-- Usuario --}}
                    <div class="mb-3">
                        <label for="usuario" class="form-label fw-semibold">
                            <i class="bi bi-person me-1"></i>Usuario
                        </label>
                        <input
                            type="text"
                            id="usuario"
                            name="usuario"
                            class="form-control form-control-lg @error('usuario') is-invalid @enderror"
                            value="{{ old('usuario') }}"
                            placeholder="Ingresa tu usuario"
                            autofocus
                            autocomplete="username"
                        >
                        @error('usuario')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Contraseña --}}
                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">
                            <i class="bi bi-lock me-1"></i>Contraseña
                        </label>
                        <div class="input-group">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control form-control-lg @error('password') is-invalid @enderror"
                                placeholder="Ingresa tu contraseña"
                                autocomplete="current-password"
                            >
                            <button
                                class="btn btn-outline-secondary"
                                type="button"
                                onclick="togglePassword()"
                                tabindex="-1"
                            >
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Botón submit --}}
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-login btn-lg text-white">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                        </button>
                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <div class="card-footer text-center text-muted py-3" style="border-radius: 0 0 16px 16px;">
                <small>UAGRM &mdash; FICCT &copy; {{ date('Y') }}</small>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePassword() {
        const input   = document.getElementById('password');
        const icon    = document.getElementById('eyeIcon');
        const visible = input.type === 'text';
        input.type    = visible ? 'password' : 'text';
        icon.className = visible ? 'bi bi-eye' : 'bi bi-eye-slash';
    }
</script>
@endpush