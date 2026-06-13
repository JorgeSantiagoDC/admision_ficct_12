@extends('layouts.app')

@section('title', 'Asignar Docente al Grupo')

@section('content')
<div class="container py-4">

    <div class="mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-person-plus-fill me-2"></i> Asignar Docente al Grupo
        </h2>
        <small class="text-muted">CU15 — Módulo Administrador</small>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Info del grupo --}}
    <div class="card shadow-sm mb-4 border-primary">
        <div class="card-header bg-primary text-white">
            <i class="bi bi-people-fill me-1"></i> Información del Grupo
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="text-muted small">Grupo</div>
                    <div class="fw-bold fs-5">{{ $grupo->nombre }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Materia</div>
                    <div class="fw-bold">{{ $grupo->materia->nombre ?? '—' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">Cupo Máximo</div>
                    <div class="fw-bold">{{ $grupo->cupo_maximo ?? 70 }} estudiantes</div>
                </div>
                <div class="col-md-2">
                    <div class="text-muted small">Docente Actual</div>
                    <div class="fw-bold">
                        @if($grupo->docente)
                            <span class="text-success">{{ $grupo->docente->usuario->nombre }}</span>
                        @else
                            <span class="text-muted fst-italic">Ninguno</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulario de asignación --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="bi bi-person-badge me-1"></i> Seleccionar Docente
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.grupos.asignar-docente.update', $grupo) }}">
                @csrf @method('PUT')

                <div class="mb-4">
                    <label class="form-label fw-semibold">Docente <span class="text-danger">*</span></label>
                    <p class="text-muted small mb-2">
                        Los docentes resaltados en rojo ya tienen 4 grupos asignados y no pueden recibir más.
                    </p>

                    <div class="row g-3">
                        @foreach($docentes as $docente)
                        <div class="col-md-4">
                            <div class="card h-100 {{ !$docente->disponible ? 'border-danger opacity-50' : ($grupo->id_docente == $docente->id_docente ? 'border-primary' : '') }}">
                                <div class="card-body p-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                               name="id_docente"
                                               id="docente_{{ $docente->id_docente }}"
                                               value="{{ $docente->id_docente }}"
                                               {{ $grupo->id_docente == $docente->id_docente ? 'checked' : '' }}
                                               {{ !$docente->disponible && $grupo->id_docente != $docente->id_docente ? 'disabled' : '' }}>
                                        <label class="form-check-label w-100" for="docente_{{ $docente->id_docente }}">
                                            <div class="fw-semibold">
                                                {{ $docente->nombres }} {{ $docente->apellido_paterno }}
                                            </div>
                                            <div class="mt-1">
                                                <small class="text-muted">Grupos asignados: </small>
                                                <span class="badge bg-{{ $docente->grupos_asignados >= 4 ? 'danger' : ($docente->grupos_asignados >= 3 ? 'warning' : 'success') }}">
                                                    {{ $docente->grupos_asignados }}/4
                                                </span>
                                            </div>
                                            @if(!$docente->disponible && $grupo->id_docente != $docente->id_docente)
                                                <div class="mt-1">
                                                    <small class="text-danger"><i class="bi bi-x-circle me-1"></i>Carga completa</small>
                                                </div>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @error('id_docente')
                        <div class="text-danger mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Confirmar Asignación
                    </button>
                    <a href="{{ route('admin.grupos.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg me-1"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
