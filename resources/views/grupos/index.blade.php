@extends('layouts.app')

@section('title', 'Grupos Académicos')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Grupos Académicos</h2>
            <small class="text-muted">CU15 — Asignación de Docentes</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Grupo</th>
                            <th>Materia</th>
                            <th>Cupo</th>
                            <th>Inscritos</th>
                            <th>Docente Asignado</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($grupos as $grupo)
                        <tr>
                            <td><strong>{{ $grupo->nombre }}</strong></td>
                            <td>{{ $grupo->materia->nombre ?? '—' }}</td>
                            <td>{{ $grupo->cupo_maximo ?? 70 }}</td>
                            <td>
                                @php $inscritos = $grupo->inscripcionGrupos->count() ?? 0; @endphp
                                <span class="badge bg-{{ $inscritos >= ($grupo->cupo_maximo ?? 70) ? 'danger' : 'success' }}">
                                    {{ $inscritos }}
                                </span>
                            </td>
                            <td>
                                @if($grupo->docente)
                                    <span class="text-success fw-semibold">
                                        <i class="bi bi-person-check me-1"></i>
                                        {{ $grupo->docente->nombres }} {{ $grupo->docente->apellido_paterno }}
                                    </span>
                                @else
                                    <span class="text-muted fst-italic">
                                        <i class="bi bi-person-x me-1"></i> Sin docente
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.grupos.asignar-docente.edit', $grupo) }}"
                                   class="btn btn-sm btn-outline-primary me-1" title="Asignar Docente">
                                    <i class="bi bi-person-plus"></i> Asignar
                                </a>
                                @if($grupo->id_docente)
                                <form action="{{ route('admin.grupos.quitar-docente', $grupo) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Quitar el docente de este grupo?')">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-outline-danger" title="Quitar Docente">
                                        <i class="bi bi-person-dash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                No hay grupos creados aún.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($grupos->hasPages())
        <div class="card-footer">{{ $grupos->links() }}</div>
        @endif
    </div>

</div>
@endsection
