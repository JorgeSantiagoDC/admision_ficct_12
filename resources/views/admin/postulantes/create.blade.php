@extends('layouts.app')

@section('title', isset($postulante) ? 'Editar Postulante' : 'Nuevo Postulante')

@section('content')
<div class="container py-4">

    <div class="mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-person-{{ isset($postulante) ? 'gear' : 'plus' }}-fill me-2"></i>
            {{ isset($postulante) ? 'Editar Postulante' : 'Registrar Nuevo Postulante' }}
        </h2>
        <small class="text-muted">CU3 — Módulo Administrador</small>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ isset($postulante) ? route('admin.postulantes.update', $postulante) : route('admin.postulantes.store') }}">
                @csrf
                @if(isset($postulante)) @method('PUT') @endif

                <h5 class="mb-3 text-primary"><i class="bi bi-person me-1"></i> Datos Personales</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">CI <span class="text-danger">*</span></label>
                        <input type="text" name="ci" class="form-control @error('ci') is-invalid @enderror"
                               value="{{ old('ci', $postulante->usuario->ci ?? '') }}" placeholder="Ej: 1234567">
                        @error('ci') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre', $postulante->usuario->nombre ?? '') }}" placeholder="Nombres">
                        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Apellido <span class="text-danger">*</span></label>
                        <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror"
                               value="{{ old('apellido', $postulante->usuario->apellido ?? '') }}" placeholder="Apellidos">
                        @error('apellido') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror"
                               value="{{ old('correo', $postulante->correo ?? '') }}" placeholder="correo@ejemplo.com">
                        @error('correo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Teléfono</label>
                        <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                               value="{{ old('telefono', $postulante->telefono ?? '') }}" placeholder="Ej: 70000000">
                        @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Fecha de Nacimiento <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                               value="{{ old('fecha_nacimiento', isset($postulante) ? \Carbon\Carbon::parse($postulante->fecha_nacimiento)->format('Y-m-d') : '') }}">
                        <div class="form-text">Mínimo 16 años de edad.</div>
                        @error('fecha_nacimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <h5 class="mb-3 text-primary"><i class="bi bi-mortarboard me-1"></i> Preferencias de Carrera</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">1ª Opción de Carrera <span class="text-danger">*</span></label>
                        <select name="id_carrera_1" class="form-select @error('id_carrera_1') is-invalid @enderror">
                            <option value="">-- Seleccionar --</option>
                            @foreach($carreras as $carrera)
                                <option value="{{ $carrera->id_carrera }}"
                                    {{ old('id_carrera_1', $postulante->id_carrera_1 ?? '') == $carrera->id_carrera ? 'selected' : '' }}>
                                    {{ $carrera->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_carrera_1') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">2ª Opción de Carrera <span class="text-danger">*</span></label>
                        <select name="id_carrera_2" class="form-select @error('id_carrera_2') is-invalid @enderror">
                            <option value="">-- Seleccionar --</option>
                            @foreach($carreras as $carrera)
                                <option value="{{ $carrera->id_carrera }}"
                                    {{ old('id_carrera_2', $postulante->id_carrera_2 ?? '') == $carrera->id_carrera ? 'selected' : '' }}>
                                    {{ $carrera->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Debe ser diferente a la 1ª opción.</div>
                        @error('id_carrera_2') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                @if(!isset($postulante))
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-1"></i>
                    La contraseña inicial del postulante será su número de CI. Puede cambiarse después desde el módulo de usuarios.
                </div>
                @endif

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>
                        {{ isset($postulante) ? 'Guardar Cambios' : 'Registrar Postulante' }}
                    </button>
                    <a href="{{ route('admin.postulantes.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg me-1"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
