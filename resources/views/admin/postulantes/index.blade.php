@extends('layouts.app')

@section('title', 'Gestionar Postulantes')

@section('content')
<div class="container-fluid py-4">

    {{-- Cabecera --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0 fw-bold">Gestionar Postulantes</h2>
            <small class="text-muted">CU3 — Módulo Administrador</small>
        </div>
        <a href="{{ route('admin.postulantes.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus-fill me-1"></i> Nuevo Postulante
        </a>
    </div>

    {{-- Alertas --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Buscador --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.postulantes.index') }}" class="row g-2">
                <div class="col-md-10">
                    <input type="text" name="buscar" class="form-control" placeholder="Buscar por CI, nombre, apellido o correo..." value="{{ request('buscar') }}">
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-search me-1"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>CI</th>
                            <th>Nombre Completo</th>
                            <th>Correo</th>
                            <th>Carrera 1ª Opción</th>
                            <th>Estado Admisión</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($postulantes as $postulante)
                        <tr>
                            <td><code>{{ $postulante->usuario->ci }}</code></td>
                            <td>{{ $postulante->usuario->nombre }} {{ $postulante->usuario->apellido }}</td>
                            <td>{{ $postulante->correo }}</td>
                            <td>{{ $postulante->carrera1->nombre ?? '—' }}</td>
                            <td>
                                @php
                                    $estado = $postulante->estado_admision;
                                    $badge = match($estado) {
                                        'Pendiente'  => 'secondary',
                                        'En Proceso' => 'warning',
                                        'Aprobado'   => 'success',
                                        'Reprobado'  => 'danger',
                                        default      => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badge }}">{{ $estado }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.postulantes.show', $postulante) }}" class="btn btn-sm btn-outline-info me-1" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.postulantes.edit', $postulante) }}" class="btn btn-sm btn-outline-warning me-1" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.postulantes.destroy', $postulante) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Seguro que deseas eliminar este postulante? Esta acción no se puede deshacer.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                No se encontraron postulantes.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($postulantes->hasPages())
        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">Mostrando {{ $postulantes->firstItem() }}–{{ $postulantes->lastItem() }} de {{ $postulantes->total() }} postulantes</small>
            {{ $postulantes->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
