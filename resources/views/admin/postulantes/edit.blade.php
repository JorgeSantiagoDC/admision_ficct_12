@extends('layouts.app')

@section('title', 'Editar Postulante')

@section('content')
<div class="container py-4">

    <div class="mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-person-gear-fill me-2"></i> Editar Postulante
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
            <form method="POST" action="{{ route('admin.postulantes.update', $postulante) }}">
                @csrf
                @method('PUT')

                <h5 class="mb-3 text-primary"><i class="bi bi-person me-1"></i> Datos Personales</h5>
                <div class="row g-3 mb-4">

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">CI <span class="text-danger">*</span></label>
                        <input type="text" name="ci"
                               class="form-control @error('ci') is-invalid @enderror"
                               {{-- ✅ ci está en postulante, no en usuario --}}
                               value="{{ old('ci', $postulante->ci) }}"
                               placeholder="Ej: 1234567">
                        @error('ci') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Nombres <span class="text-danger">*</span></label>
                        <input type="text" name="nombres"
                               class="form-control @error('nombres') is-invalid @enderror"
                               {{-- ✅ nombres está en postulante --}}
                               value="{{ old('nombres', $postulante->nombres) }}"
                               placeholder="Nombres completos">
                        @error('nombres') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Apellido Paterno <span class="text-danger">*</span></label>
                        <input type="text" name="apellido_paterno"
                               class="form-control @error('apellido_paterno') is-invalid @enderror"
                               value="{{ old('apellido_paterno', $postulante->apellido_paterno) }}"
                               placeholder="Apellido paterno">
                        @error('apellido_paterno') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Apellido Materno <span class="text-danger">*</span></label>
                        <input type="text" name="apellido_materno"
                               class="form-control @error('apellido_materno') is-invalid @enderror"
                               value="{{ old('apellido_materno', $postulante->apellido_materno) }}"
                               placeholder="Apellido materno">
                        @error('apellido_materno') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" name="correo"
                               class="form-control @error('correo') is-invalid @enderror"
                               value="{{ old('correo', $postulante->correo) }}"
                               placeholder="correo@ejemplo.com">
                        @error('correo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Teléfono</label>
                        <input type="text" name="telefono"
                               class="form-control @error('telefono') is-invalid @enderror"
                               value="{{ old('telefono', $postulante->telefono) }}"
                               placeholder="Ej: 70000000">
                        @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Fecha de Nacimiento <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_nacimiento"
                               class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                               value="{{ old('fecha_nacimiento', \Carbon\Carbon::parse($postulante->fecha_nacimiento)->format('Y-m-d')) }}">
                        <div class="form-text">Mínimo 16 años de edad.</div>
                        @error('fecha_nacimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Sexo <span class="text-danger">*</span></label>
                        <select name="sexo" class="form-select @error('sexo') is-invalid @enderror">
                            <option value="">--</option>
                            <option value="M" {{ old('sexo', $postulante->sexo) == 'M' ? 'selected' : '' }}>
                                Masculino
                            </option>
                            <option value="F" {{ old('sexo', $postulante->sexo) == 'F' ? 'selected' : '' }}>
                                Femenino
                            </option>
                        </select>
                        @error('sexo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Ciudad</label>
                        <input type="text" name="ciudad"
                               class="form-control @error('ciudad') is-invalid @enderror"
                               value="{{ old('ciudad', $postulante->ciudad) }}"
                               placeholder="Ej: Santa Cruz">
                        @error('ciudad') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Colegio de Procedencia</label>
                        <input type="text" name="colegio_procedencia"
                               class="form-control @error('colegio_procedencia') is-invalid @enderror"
                               value="{{ old('colegio_procedencia', $postulante->colegio_procedencia) }}"
                               placeholder="Nombre del colegio">
                        @error('colegio_procedencia') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                </div>

                <h5 class="mb-3 text-primary"><i class="bi bi-mortarboard me-1"></i> Preferencias de Carrera</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">1ª Opción de Carrera <span class="text-danger">*</span></label>
                        <select name="id_carrera_opcion1" class="form-select @error('id_carrera_opcion1') is-invalid @enderror">
                            <option value="">-- Seleccionar --</option>
                            @foreach($carreras as $carrera)
                                <option value="{{ $carrera->id_carrera }}"
                                    {{-- ✅ campo correcto: id_carrera_opcion1 --}}
                                    {{ old('id_carrera_opcion1', $postulante->id_carrera_opcion1) == $carrera->id_carrera ? 'selected' : '' }}>
                                    {{ $carrera->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_carrera_opcion1') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-semibold">2ª Opción de Carrera <span class="text-danger">*</span></label>
                        <select name="id_carrera_opcion2" class="form-select @error('id_carrera_opcion2') is-invalid @enderror">
                            <option value="">-- Seleccionar --</option>
                            @foreach($carreras as $carrera)
                                <option value="{{ $carrera->id_carrera }}"
                                    {{-- ✅ campo correcto: id_carrera_opcion2 --}}
                                    {{ old('id_carrera_opcion2', $postulante->id_carrera_opcion2) == $carrera->id_carrera ? 'selected' : '' }}>
                                    {{ $carrera->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Debe ser diferente a la 1ª opción.</div>
                        @error('id_carrera_opcion2') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Info del postulante actual --}}
                <div class="alert alert-secondary d-flex align-items-center gap-2 mb-4">
                    <i class="bi bi-info-circle fs-5"></i>
                    <div>
                        {{-- ✅ ci está en postulante --}}
                        Editando el postulante <strong>{{ $postulante->ci }}</strong>.
                        La contraseña no se modifica desde aquí.
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-check-lg me-1"></i> Guardar Cambios
                    </button>
                    <a href="{{ route('admin.postulantes.show', $postulante) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg me-1"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection