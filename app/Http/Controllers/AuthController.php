<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Muestra la vista de login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesa el login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('nombre', $request->nombre)->first();

        if ($user && $user->password === $request->password) {
            Auth::login($user);
            return redirect()->route('panel');
        }

        return redirect()->route('login')->with('error', 'Usuario o contraseña incorrectos.');
    }



    /**
     * Cierra la sesión.
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
