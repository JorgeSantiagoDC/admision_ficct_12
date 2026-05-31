<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Rol;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // Roles
        $admin      = Rol::create(['nombre' => 'Administrador']);
        $docente    = Rol::create(['nombre' => 'Docente']);
        $postulante = Rol::create(['nombre' => 'Postulante']);

        // Usuarios de prueba
        Usuario::create([
            'id_rol'   => $admin->id_rol,
            'usuario'  => 'admin_ficct',
            'password' => Hash::make('admin123'),
            'activo'   => true,
        ]);

        Usuario::create([
            'id_rol'   => $docente->id_rol,
            'usuario'  => 'docente_ficct',
            'password' => Hash::make('docente123'),
            'activo'   => true,
        ]);

        Usuario::create([
            'id_rol'   => $postulante->id_rol,
            'usuario'  => 'postulante_ficct',
            'password' => Hash::make('postulante123'),
            'activo'   => true,
        ]);
    }
}