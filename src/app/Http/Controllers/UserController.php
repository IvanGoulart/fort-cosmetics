<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Exibe a lista pública de usuários (paginada)
     */
    public function index()
    {
        $users = User::orderBy('name')->paginate(12);

        return view('users.index', compact('users'));
    }

     /**
     * Exibe o perfil público de um usuário e seus cosméticos
     */
    public function show($id)
    {
        $user = User::with(['cosmetics' => function ($query) {
            $query->orderBy('name');
        }])->findOrFail($id);

        return view('users.show', compact('user'));
    }
}
