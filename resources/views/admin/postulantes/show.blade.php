@extends('layouts.app')

@section('title', 'Detalle del Postulante')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-person-lines-fill me-2"></i>
                {{-- ✅ Los nombres están en postulante, no en usuario --}}
                {{ $postulante->nombres }} {{ $postulante->apellido_paterno }} {{ $postulante->apellido_materno }}
            </h2>
            <small class="text-muted">CI: {{ $postulante->ci }}</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.postulantes.edit', $postulante) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-1"></i> Editar
            </a>
            <a href="{{ route('admin.postulantes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Datos personales --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-person me-1"></i> Datos Personales
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-5">CI:</dt>
                        <dd class="col-7"><code>{{ $postulante->ci }}</code></dd>

                        <dt class="col-5">Nombres:</dt>
                        <dd class="col-7">{{ $postulante->nombres }}</dd>

                        <dt class="col-5">Apellido Paterno:</dt>
                        <dd class="col-7">{{ $postulante->apellido_paterno }}</dd>

                        <dt class="col-5">Apellido Materno:</dt>
                        <dd class="col-7">{{ $postulante->apellido_materno }}</dd>

                        <dt class="col-5">Sexo:</dt>
                        <dd class="col-7">{{ $postulante->sexo == 'M' ? 'Masculino' : 'Femenino' }}</dd>

                        <dt class="col-5">Correo:</dt>
                        <dd class="col-7">{{ $postulante->correo }}</dd>

                        <dt class="col-5">Teléfono:</dt>
                        <dd class="col-7">{{ $postulante->telefono ?? '—' }}</dd>

                        <dt class="col-5">Nacimiento:</dt>
                        <dd class="col-7">
                            {{ \Carbon\Carbon::parse($postulante->fecha_nacimiento)->format('d/m/Y') }}
                        </dd>

                        <dt class="col-5">Ciudad:</dt>
                        <dd class="col-7">{{ $postulante->ciudad ?? '—' }}</dd>

                        <dt class="col-5">Colegio:</dt>
                        <dd class="col-7">{{ $postulante->colegio_procedencia ?? '—' }}</dd>

                        <dt class="col-5">Promedio Final:</dt>
                        <dd class="col-7">
                            <span class="fw-bold {{ $postulante->promedio_final >= 60 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($postulante->promedio_final, 2) }}
                            </span>
                        </dd>

                        <dt class="col-5">Estado:</dt>
                        <dd class="col-7">
                            @php
                                $badge = match($postulante->estado_admision) {
                                    'Pendiente'  => 'secondary',
                                    'En Proceso' => 'warning',
                                    'Aprobado'   => 'success',
                                    'Reprobado'  => 'danger',
                                    default      => 'secondary',
                                };
                            @endphp
                            <span class="badge bg-{{ $badge }} fs-6">
                                {{ $postulante->estado_admision }}
                            </span>
                        </dd>

                        <dt class="col-5">Usuario:</dt>
                        <dd class="col-7"><code>{{ $postulante->usuario->usuario }}</code></dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Preferencias de carrera --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <i class="bi bi-mortarboard me-1"></i> Preferencias de Carrera
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="badge bg-primary me-2">1ª Opción</span>
                        {{-- ✅ Relación correcta: carreraOpcion1 --}}
                        <strong>{{ $postulante->carreraOpcion1->nombre ?? '—' }}</strong>
                    </div>
                    <div>
                        <span class="badge bg-secondary me-2">2ª Opción</span>
                        {{-- ✅ Relación correcta: carreraOpcion2 --}}
                        <strong>{{ $postulante->carreraOpcion2->nombre ?? '—' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grupos inscritos --}}
        @if($postulante->grupos->isNotEmpty())
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <i class="bi bi-people me-1"></i> Grupos Académicos Asignados
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Grupo</th>
                                <th>Materia</th>
                                <th>Docente</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- ✅ Relación correcta: grupos (belongsToMany) --}}
                            @foreach($postulante->grupos as $grupo)
                            <tr>
                                <td>{{ $grupo->nombre_grupo }}</td>
                                <td>{{ $grupo->materia->nombre ?? '—' }}</td>
                                <td>
                                    {{ $grupo->docente->nombres ?? '—' }}
                                    {{ $grupo->docente->apellido_paterno ?? '' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection