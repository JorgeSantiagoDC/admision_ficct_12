@extends('layouts.app')
@section('title', 'Panel Administrador')
@section('content')
<div class="container-fluid">
    <h2 class="mb-4">
        <i class="bi bi-speedometer2 me-2 text-primary"></i>
        Panel del Administrador
    </h2>
    <div class="alert alert-success">
        <i class="bi bi-check-circle me-2"></i>
        Bienvenido, <strong>{{ Auth::user()->usuario }}</strong>. Sesión iniciada correctamente.
    </div>
</div>
@endsection
