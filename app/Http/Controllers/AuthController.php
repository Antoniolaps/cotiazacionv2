<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogger;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Intentar autenticar por username / password_hash
        $user = User::where('username', $credentials['username'])->first();

        if ($user && \Hash::check($credentials['password'], $user->password_hash)) {
            if (!$user->estado) {
                return back()->withErrors(['username' => 'Usuario inactivo. Contacte al administrador.']);
            }

            Auth::login($user);
            $user->ultimo_login = now();
            $user->save();

            ActivityLogger::log('LOGIN', 'usuarios', $user->id);

            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['username' => 'Credenciales incorrectas.']);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            ActivityLogger::log('LOGOUT', 'usuarios', Auth::id());
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
