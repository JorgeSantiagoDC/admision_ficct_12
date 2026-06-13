@extends('layouts.app')
@section('title', 'Panel Administrador')

@section('content')
<div class="container-fluid py-4">

    {{-- Bienvenida --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-speedometer2 me-2 text-primary"></i>
                Panel del Administrador
            </h2>
            <small class="text-muted">Sistema de Admisión FICCT — UAGRM</small>
        </div>
        <span class="badge bg-primary fs-6">
            <i class="bi bi-person-circle me-1"></i>
            {{ Auth::user()->usuario }}
        </span>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tarjetas de acceso rápido --}}
    <div class="row g-4">

        {{-- CU3 — Gestionar Postulantes --}}
        <div class="col-md-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex
                                align-items-center justify-content-center mb-3"
                         style="width:64px;height:64px;">
                        <i class="bi bi-people-fill text-primary fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Gestionar Postulantes</h5>
                    <p class="text-muted small mb-3">
                        Registra, edita y consulta los postulantes del proceso de admisión.
                    </p>
                    <a href="{{ route('admin.postulantes.index') }}" class="btn btn-primary w-100">
                        <i class="bi bi-arrow-right-circle me-1"></i> Ir al módulo
                    </a>
                </div>
                <div class="card-footer text-muted small text-center bg-transparent">
                    CU3 — Módulo Postulantes
                </div>
            </div>
        </div>

        {{-- CU15 — Asignar Docente a Grupo --}}
        <div class="col-md-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex
                                align-items-center justify-content-center mb-3"
                         style="width:64px;height:64px;">
                        <i class="bi bi-person-badge-fill text-success fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Asignar Docentes a Grupos</h5>
                    <p class="text-muted small mb-3">
                        Gestiona la asignación de docentes a los grupos académicos del proceso.
                    </p>
                    <a href="{{ route('admin.grupos.index') }}" class="btn btn-success w-100">
                        <i class="bi bi-arrow-right-circle me-1"></i> Ir al módulo
                    </a>
                </div>
                <div class="card-footer text-muted small text-center bg-transparent">
                    CU15 — Módulo Grupos
                </div>
            </div>
        </div>

        {{-- Próximamente --}}
        <div class="col-md-4">
            <div class="card shadow-sm h-100 border-0 opacity-50">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-secondary bg-opacity-10 d-inline-flex
                                align-items-center justify-content-center mb-3"
                         style="width:64px;height:64px;">
                        <i class="bi bi-clock-history text-secondary fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-muted">Más módulos</h5>
                    <p class="text-muted small mb-3">
                        Gestión de materias, docentes, gestiones académicas y más.
                    </p>
                    <button class="btn btn-secondary w-100" disabled>
                        <i class="bi bi-lock me-1"></i> Próximamente
                    </button>
                </div>
                <div class="card-footer text-muted small text-center bg-transparent">
                    CU10, CU11, CU12, CU13, CU14...
                </div>
            </div>
        </div>

    </div>

    {{-- Resumen de casos de uso implementados --}}
    <div class="card shadow-sm mt-4 border-0">
        <div class="card-header bg-transparent fw-bold">
            <i class="bi bi-list-check me-2 text-primary"></i>
            Estado del Ciclo #1 — 15 Casos de Uso
        </div>
        <div class="card-body">
            <div class="row g-2">
                @php
                    $casos = [
                        ['cu' => 'CU1',  'nombre' => 'Iniciar Sesión',                  'estado' => 'done'],
                        ['cu' => 'CU2',  'nombre' => 'Cerrar Sesión',                   'estado' => 'done'],
                        ['cu' => 'CU3',  'nombre' => 'Gestionar Postulantes',            'estado' => 'done'],
                        ['cu' => 'CU4',  'nombre' => 'Subir Requisitos Documentales',   'estado' => 'pending'],
                        ['cu' => 'CU5',  'nombre' => 'Validar Requisitos Documentales', 'estado' => 'pending'],
                        ['cu' => 'CU6',  'nombre' => 'Consultar Estado de Inscripción', 'estado' => 'pending'],
                        ['cu' => 'CU7',  'nombre' => 'Registrar Preferencia de Carrera','estado' => 'pending'],
                        ['cu' => 'CU8',  'nombre' => 'Registrar Pago de Inscripción',   'estado' => 'pending'],
                        ['cu' => 'CU9',  'nombre' => 'Validar Pago',                    'estado' => 'pending'],
                        ['cu' => 'CU10', 'nombre' => 'Gestionar Materias',              'estado' => 'pending'],
                        ['cu' => 'CU11', 'nombre' => 'Administrar Gestiones Académicas','estado' => 'pending'],
                        ['cu' => 'CU12', 'nombre' => 'Gestionar Docentes',              'estado' => 'pending'],
                        ['cu' => 'CU13', 'nombre' => 'Calcular Cantidad de Grupos',     'estado' => 'pending'],
                        ['cu' => 'CU14', 'nombre' => 'Crear Grupos Académicos',         'estado' => 'pending'],
                        ['cu' => 'CU15', 'nombre' => 'Asignar Docente a Grupo',         'estado' => 'done'],
                    ];
                @endphp

                @foreach($casos as $caso)
                <div class="col-md-4">
                    <div class="d-flex align-items-center gap-2 p-2 rounded
                        {{ $caso['estado'] === 'done' ? 'bg-success bg-opacity-10' : 'bg-light' }}">
                        <i class="bi {{ $caso['estado'] === 'done' ? 'bi-check-circle-fill text-success' : 'bi-circle text-muted' }}"></i>
                        <span class="badge {{ $caso['estado'] === 'done' ? 'bg-success' : 'bg-secondary' }}">
                            {{ $caso['cu'] }}
                        </span>
                        <small class="{{ $caso['estado'] === 'done' ? 'fw-semibold' : 'text-muted' }}">
                            {{ $caso['nombre'] }}
                        </small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection