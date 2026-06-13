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
    /**
     * CU3 - Listar todos los postulantes
     */
    public function index(Request $request)
    {
        $query = Postulante::with(['usuario', 'carrera1', 'carrera2']);

        // Búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->whereHas('usuario', function ($q) use ($buscar) {
                $q->where('nombre', 'ilike', "%{$buscar}%")
                  ->orWhere('apellido', 'ilike', "%{$buscar}%")
                  ->orWhere('ci', 'ilike', "%{$buscar}%")
                  ->orWhere('correo', 'ilike', "%{$buscar}%");
            });
        }

        $postulantes = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.postulantes.index', compact('postulantes'));
    }

    /**
     * CU3 - Mostrar formulario de creación
     */
    public function create()
    {
        $carreras = Carrera::orderBy('nombre')->get();
        return view('admin.postulantes.create', compact('carreras'));
    }

    /**
     * CU3 - Guardar nuevo postulante
     */
    public function store(Request $request)
    {
        $request->validate([
            'ci'                => 'required|string|max:20|unique:usuario,ci',
            'nombre'            => 'required|string|max:100',
            'apellido'          => 'required|string|max:100',
            'correo'            => 'required|email|max:150|unique:postulante,correo',
            'telefono'          => 'nullable|string|max:20',
            'fecha_nacimiento'  => 'required|date|before:-16 years',
            'id_carrera_1'      => 'required|exists:carrera,id_carrera',
            'id_carrera_2'      => 'required|exists:carrera,id_carrera|different:id_carrera_1',
        ], [
            'ci.unique'              => 'Ya existe un usuario registrado con ese CI.',
            'correo.unique'          => 'Ya existe un postulante con ese correo electrónico.',
            'fecha_nacimiento.before'=> 'El postulante debe tener al menos 16 años.',
            'id_carrera_2.different' => 'La segunda opción de carrera debe ser diferente a la primera.',
        ]);

        DB::transaction(function () use ($request) {
            // Obtener el rol de postulante
            $rol = Rol::where('nombre', 'postulante')->firstOrFail();

            // Crear usuario
            $usuario = Usuario::create([
                'ci'           => $request->ci,
                'nombre'       => $request->nombre,
                'apellido'     => $request->apellido,
                'contrasena'   => Hash::make($request->ci), // Contraseña por defecto = CI
                'id_rol'       => $rol->id_rol,
            ]);

            // Crear postulante
            Postulante::create([
                'id_usuario'       => $usuario->id_usuario,
                'correo'           => $request->correo,
                'telefono'         => $request->telefono,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'id_carrera_1'     => $request->id_carrera_1,
                'id_carrera_2'     => $request->id_carrera_2,
                'estado_admision'  => 'Pendiente',
            ]);
        });

        return redirect()->route('admin.postulantes.index')
            ->with('success', 'Postulante registrado correctamente. Contraseña inicial: ' . $request->ci);
    }

    /**
     * CU3 - Mostrar detalle de un postulante
     */
    public function show(Postulante $postulante)
    {
        $postulante->load(['usuario', 'carrera1', 'carrera2', 'requisitos', 'pagos', 'inscripcionGrupos.grupo']);
        return view('admin.postulantes.show', compact('postulante'));
    }

    /**
     * CU3 - Mostrar formulario de edición
     */
    public function edit(Postulante $postulante)
    {
        $postulante->load('usuario');
        $carreras = Carrera::orderBy('nombre')->get();
        return view('admin.postulantes.edit', compact('postulante', 'carreras'));
    }

    /**
     * CU3 - Actualizar postulante
     */
    public function update(Request $request, Postulante $postulante)
    {
        $request->validate([
            'ci'                => ['required', 'string', 'max:20', Rule::unique('usuario', 'ci')->ignore($postulante->id_usuario, 'id_usuario')],
            'nombre'            => 'required|string|max:100',
            'apellido'          => 'required|string|max:100',
            'correo'            => ['required', 'email', 'max:150', Rule::unique('postulante', 'correo')->ignore($postulante->id_postulante, 'id_postulante')],
            'telefono'          => 'nullable|string|max:20',
            'fecha_nacimiento'  => 'required|date|before:-16 years',
            'id_carrera_1'      => 'required|exists:carrera,id_carrera',
            'id_carrera_2'      => 'required|exists:carrera,id_carrera|different:id_carrera_1',
        ], [
            'ci.unique'              => 'Ya existe otro usuario con ese CI.',
            'correo.unique'          => 'Ya existe otro postulante con ese correo.',
            'fecha_nacimiento.before'=> 'El postulante debe tener al menos 16 años.',
            'id_carrera_2.different' => 'La segunda opción de carrera debe ser diferente a la primera.',
        ]);

        DB::transaction(function () use ($request, $postulante) {
            // Actualizar usuario
            $postulante->usuario->update([
                'ci'      => $request->ci,
                'nombre'  => $request->nombre,
                'apellido'=> $request->apellido,
            ]);

            // Actualizar postulante
            $postulante->update([
                'correo'           => $request->correo,
                'telefono'         => $request->telefono,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'id_carrera_1'     => $request->id_carrera_1,
                'id_carrera_2'     => $request->id_carrera_2,
            ]);
        });

        return redirect()->route('admin.postulantes.index')
            ->with('success', 'Postulante actualizado correctamente.');
    }

    /**
     * CU3 - Eliminar postulante
     */
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
