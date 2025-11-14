<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth; // Agregar esta línea

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        // Validación de datos
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required'
        ], [
            'terms.required' => 'Acepte terminos y condiciones.',
            'email.unique' => 'Este correo ya esta en uso.',
            'name.unique' => 'Este nombre de usuario ya esta en uso.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe de tener al menos 8 caracteres.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Redirigir al login con mensaje de éxito
        return redirect()->route('login')->with('success', '¡Registro exitoso! Inicie sesion.');
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required',
        ]);

        // Determinar si el campo es email o name (username)
        $loginType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        // Credenciales para el intento de login
        $credentials = [
            $loginType => $request->email,
            'password' => $request->password
        ];

        // Usar Auth::attempt en lugar de auth()->attempt
        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Usuario/Email o contraseña incorrectos.',
        ]);
    }

    public function logout(Request $request)
    {
        // Usar Auth::logout en lugar de auth()->logout
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
