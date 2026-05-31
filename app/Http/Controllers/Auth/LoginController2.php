<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Muestra el formulario de login
    public function showLoginForm()
    {
        // Si ya tiene sesión activa, redirige a su panel
        if (Auth::check()) {
            return $this->redirigirSegunRol();
        }

        return view('auth.login');
    }

    // Procesa el intento de login
    public function login(Request $request)
    {
        // Validación de campos vacíos
        $request->validate([
            'usuario'  => 'required|string',
            'password' => 'required|string',
        ], [
            'usuario.required'  => 'El nombre de usuario es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        $credenciales = [
            'usuario'  => $request->usuario,
            'password' => $request->password,
        ];

        // Verifica credenciales + que el usuario esté activo
        if (!Auth::attempt($credenciales)) {
            return back()
                ->withInput($request->only('usuario'))
                ->withErrors([
                    'usuario' => 'Usuario o contraseña incorrectos.',
                ]);
        }

        // Verifica que la cuenta esté habilitada
        if (!Auth::user()->activo) {
            Auth::logout();
            return back()
                ->withInput($request->only('usuario'))
                ->withErrors([
                    'usuario' => 'Tu cuenta está deshabilitada. Contacta al administrador.',
                ]);
        }

        // Regenera sesión (protección CSRF)
        $request->session()->regenerate();

        return $this->redirigirSegunRol();
    }

    // Cierra la sesión (CU2)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Sesión cerrada correctamente.');
    }

    // Redirige según el rol del usuario autenticado
    private function redirigirSegunRol()
    {
        $rol = Auth::user()->rol->nombre;

        return match($rol) {
            'Administrador' => redirect()->route('admin.dashboard'),
            'Docente'       => redirect()->route('docente.dashboard'),
            'Postulante'    => redirect()->route('postulante.dashboard'),
            default         => redirect()->route('login')
                                ->withErrors(['usuario' => 'Rol no reconocido.']),
        };
    }
}