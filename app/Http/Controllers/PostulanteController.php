<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use App\Models\Carrera;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PostulanteController extends Controller
{
    // CU3 - Listar todos los postulantes
    public function index(Request $request)
    {
        $query = Postulante::with(['usuario', 'carreraOpcion1', 'carreraOpcion2']);

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('nombres', 'ilike', "%{$buscar}%")
                  ->orWhere('apellido_paterno', 'ilike', "%{$buscar}%")
                  ->orWhere('ci', 'ilike', "%{$buscar}%")
                  ->orWhere('correo', 'ilike', "%{$buscar}%");
            });
        }

        $postulantes = $query->orderBy('id_postulante', 'desc')->paginate(15);

        return view('admin.postulantes.index', compact('postulantes'));
    }

    // CU3 - Mostrar formulario de creación
    public function create()
    {
        $carreras = Carrera::orderBy('nombre')->get();
        return view('admin.postulantes.create', compact('carreras'));
    }

    // CU3 - Guardar nuevo postulante
    public function store(Request $request)
    {
        $request->validate([
            'ci'               => 'required|string|max:20|unique:postulante,ci',
            'nombres'          => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'required|string|max:100',
            'correo'           => 'required|email|max:100|unique:postulante,correo',
            'telefono'         => 'nullable|string|max:20',
            'fecha_nacimiento' => 'required|date|before:-16 years',
            'sexo'             => 'required|in:M,F',
            'id_carrera_opcion1' => 'required|exists:carrera,id_carrera',
            'id_carrera_opcion2' => 'required|exists:carrera,id_carrera|different:id_carrera_opcion1',
        ], [
            'ci.unique'                    => 'Ya existe un postulante con ese CI.',
            'correo.unique'                => 'Ya existe un postulante con ese correo.',
            'fecha_nacimiento.before'      => 'El postulante debe tener al menos 16 años.',
            'id_carrera_opcion2.different' => 'La segunda opción debe ser diferente a la primera.',
        ]);

        DB::transaction(function () use ($request) {
            // Crear usuario base
            $rol = Rol::where('nombre', 'Postulante')->firstOrFail();

            $usuario = Usuario::create([
                'id_rol'   => $rol->id_rol,
                'usuario'  => $request->ci, // usuario = CI por defecto
                'password' => Hash::make($request->ci),
                'activo'   => true,
            ]);

            // Crear postulante con todos sus campos
            Postulante::create([
                'id_usuario'        => $usuario->id_usuario,
                'ci'                => $request->ci,
                'nombres'           => $request->nombres,
                'apellido_paterno'  => $request->apellido_paterno,
                'apellido_materno'  => $request->apellido_materno,
                'fecha_nacimiento'  => $request->fecha_nacimiento,
                'sexo'              => $request->sexo,
                'correo'            => $request->correo,
                'telefono'          => $request->telefono,
                'id_carrera_opcion1'=> $request->id_carrera_opcion1,
                'id_carrera_opcion2'=> $request->id_carrera_opcion2,
                'estado_admision'   => 'Pendiente',
            ]);
        });

        return redirect()->route('admin.postulantes.index')
            ->with('success', 'Postulante registrado. Usuario: ' . $request->ci . ' / Contraseña: ' . $request->ci);
    }

    // CU3 - Mostrar detalle de un postulante
    public function show(Postulante $postulante)
    {
        $postulante->load(['usuario', 'carreraOpcion1', 'carreraOpcion2', 'requisitos', 'pagos', 'grupos']);
        return view('admin.postulantes.show', compact('postulante'));
    }

    // CU3 - Mostrar formulario de edición
    public function edit(Postulante $postulante)
    {
        $postulante->load('usuario');
        $carreras = Carrera::orderBy('nombre')->get();
        return view('admin.postulantes.edit', compact('postulante', 'carreras'));
    }

    // CU3 - Actualizar postulante
    public function update(Request $request, Postulante $postulante)
    {
        $request->validate([
            'ci'               => ['required', 'string', 'max:20',
                                   Rule::unique('postulante', 'ci')->ignore($postulante->id_postulante, 'id_postulante')],
            'nombres'          => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'required|string|max:100',
            'correo'           => ['required', 'email', 'max:100',
                                   Rule::unique('postulante', 'correo')->ignore($postulante->id_postulante, 'id_postulante')],
            'telefono'         => 'nullable|string|max:20',
            'fecha_nacimiento' => 'required|date|before:-16 years',
            'sexo'             => 'required|in:M,F',
            'id_carrera_opcion1' => 'required|exists:carrera,id_carrera',
            'id_carrera_opcion2' => 'required|exists:carrera,id_carrera|different:id_carrera_opcion1',
        ], [
            'ci.unique'                    => 'Ya existe otro postulante con ese CI.',
            'correo.unique'                => 'Ya existe otro postulante con ese correo.',
            'fecha_nacimiento.before'      => 'El postulante debe tener al menos 16 años.',
            'id_carrera_opcion2.different' => 'La segunda opción debe ser diferente a la primera.',
        ]);

        DB::transaction(function () use ($request, $postulante) {
            // Actualizar usuario (solo el campo usuario si cambió el CI)
            $postulante->usuario->update([
                'usuario' => $request->ci,
            ]);

            // Actualizar postulante
            $postulante->update([
                'ci'                => $request->ci,
                'nombres'           => $request->nombres,
                'apellido_paterno'  => $request->apellido_paterno,
                'apellido_materno'  => $request->apellido_materno,
                'fecha_nacimiento'  => $request->fecha_nacimiento,
                'sexo'              => $request->sexo,
                'correo'            => $request->correo,
                'telefono'          => $request->telefono,
                'id_carrera_opcion1'=> $request->id_carrera_opcion1,
                'id_carrera_opcion2'=> $request->id_carrera_opcion2,
            ]);
        });

        return redirect()->route('admin.postulantes.index')
            ->with('success', 'Postulante actualizado correctamente.');
    }

    // CU3 - Eliminar postulante
    public function destroy(Postulante $postulante)
    {
        DB::transaction(function () use ($postulante) {
            $usuario = $postulante->usuario;
            $postulante->delete();
            $usuario->delete();
        });

        return redirect()->route('admin.postulantes.index')
            ->with('success', 'Postulante eliminado correctamente.');
    }
}