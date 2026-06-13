<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Docente;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsignacionDocenteController extends Controller
{
    private const MAX_GRUPOS_DOCENTE = 4;

    /**
     * CU15 - Listar grupos con su docente asignado
     */
    public function index()
    {
        $grupos = Grupo::with(['docente.usuario', 'materia'])
            ->orderBy('nombre_grupo') 
            ->orderBy('nombre')
            ->paginate(15);

        return view('admin.grupos.index', compact('grupos'));
    }

    /**
     * CU15 - Mostrar formulario para asignar docente a un grupo
     */
    public function edit(Grupo $grupo)
    {
        $grupo->load(['docente.usuario', 'materia']);

        // Obtener docentes con su carga actual (número de grupos asignados en la gestión activa)
        $docentes = Docente::with('usuario')
            ->withCount([
                'grupos as grupos_asignados' // requiere relación hasMany en Docente
            ])
            ->orderBy('grupos_asignados')
            ->get()
            ->map(function ($docente) {
                $docente->disponible = $docente->grupos_asignados < self::MAX_GRUPOS_DOCENTE;
                return $docente;
            });

        return view('admin.grupos.asignar_docente', compact('grupo', 'docentes'));
    }

    /**
     * CU15 - Guardar la asignación de docente al grupo
     */
    public function update(Request $request, Grupo $grupo)
    {
        $request->validate([
            'id_docente' => 'required|exists:docente,id_docente',
        ]);

        $docente = Docente::withCount('grupos as grupos_asignados')
            ->findOrFail($request->id_docente);

        // Validar que no exceda el límite de grupos (el trigger también lo hace en BD,
        // pero validamos antes para dar un mensaje amigable)
        $gruposActuales = $docente->grupos()->count();

        // Si el grupo ya tenía este mismo docente, no contarlo como nuevo
        $mismoDocente = $grupo->id_docente === $docente->id_docente;

        if (!$mismoDocente && $gruposActuales >= self::MAX_GRUPOS_DOCENTE) {
            return back()->with('error',
                "El docente {$docente->usuario->nombre} {$docente->usuario->apellido} ya tiene " .
                self::MAX_GRUPOS_DOCENTE . " grupos asignados (límite máximo). " .
                "Por favor seleccione otro docente."
            );
        }

        DB::transaction(function () use ($grupo, $request) {
            $grupo->update([
                'id_docente' => $request->id_docente,
            ]);
        });

        return redirect()->route('admin.grupos.index')
        ->with('success',
            "Docente {$docente->nombres} {$docente->apellido_paterno} " .
            "asignado correctamente al grupo {$grupo->nombre_grupo}."
        );
    }

    /**
     * CU15 - Quitar docente de un grupo
     */
    public function quitarDocente(Grupo $grupo)
    {
        // No podemos poner id_docente en null porque es NOT NULL en la BD
        // En su lugar redirigimos al formulario para asignar un nuevo docente
        return redirect()->route('admin.grupos.asignar-docente.edit', $grupo)
            ->with('info', "Selecciona un nuevo docente para el grupo {$grupo->nombre_grupo}.");
    }

}
