<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario'  => 'required|string',
            'password' => 'required|string',
        ], [
            'usuario.required'  => 'El campo usuario es obligatorio.',
            'password.required' => 'El campo contraseña es obligatorio.',
        ]);

        $credenciales = [
            'usuario'  => $request->usuario,
            'password' => $request->password,
        ];

        if (!Auth::attempt($credenciales)) {
            return back()
                ->withErrors(['usuario' => 'Usuario o contraseña incorrectos.'])
                ->withInput($request->only('usuario'));
        }

        $usuario = Auth::user();

        if (!$usuario->activo) {
            Auth::logout();
            return back()
                ->withErrors(['usuario' => 'Tu cuenta está desactivada. Contacta al administrador.'])
                ->withInput($request->only('usuario'));
        }

        $request->session()->regenerate();

        return $this->redirectByRole();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Sesión cerrada correctamente.');
    }

    private function redirectByRole()
    {
        $rol = Auth::user()->rol->nombre;

        return match ($rol) {
            'Administrador' => redirect()->route('admin.dashboard'),
            'Docente'       => redirect()->route('docente.dashboard'),
            'Postulante'    => redirect()->route('postulante.dashboard'),
            default         => redirect()->route('login'),
        };
    }
}