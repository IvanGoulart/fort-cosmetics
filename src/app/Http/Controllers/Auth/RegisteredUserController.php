<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    /**
     * Exibe o formulário de registro
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Processa o cadastro do usuário
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'vbucks' => 10000, // 💰 Créditos iniciais
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Conta criada com sucesso! Você recebeu 10.000 V-Bucks!');
    }
}
