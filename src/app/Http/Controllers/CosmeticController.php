<?php

namespace App\Http\Controllers;

use App\Models\Cosmetic;
use Illuminate\Http\Request;

class CosmeticController extends Controller
{
    /**
     * Exibe uma lista de cosméticos.
     */
     public function index(Request $request)
    {
        $query = Cosmetic::query();

        // Filtro por nome
        if ($request->filled('nome')) {
            $query->where('name', 'like', '%' . $request->nome . '%');
        }

        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->where('type', 'like', '%' . $request->tipo . '%');
        }

        // Filtro por raridade
        if ($request->filled('raridade')) {
            $query->where('rarity', 'like', '%' . $request->raridade . '%');
        }

        // Paginação
        $cosmetics = $query->paginate(12)->withQueryString();

        return view('cosmetics.index', compact('cosmetics'));
    }

    /**
     * Mostra o formulário de criação (caso use Blade).
     */
    public function create()
    {
        return view('cosmetics.create');
    }

    /**
     * Armazena um novo cosmético no banco.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'rarity' => 'nullable|string',
            'type' => 'nullable|string',
            'series' => 'nullable|string',
            'set' => 'nullable|string',
            'introduction' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        $cosmetic = Cosmetic::create($data);

        return response()->json([
            'message' => 'Cosmético criado com sucesso!',
            'data' => $cosmetic
        ]);
    }

    /**
     * Exibe um cosmético específico.
     */
    public function show($id)
    {
        $cosmetic = Cosmetic::findOrFail($id);
        return view('cosmetics.show', compact('cosmetic'));
    }

    /**
     * Mostra o formulário de edição (caso use Blade).
     */
    public function edit($id)
    {
        $cosmetic = Cosmetic::findOrFail($id);
        return view('cosmetics.edit', compact('cosmetic'));
    }

    /**
     * Atualiza um cosmético existente.
     */
    public function update(Request $request, $id)
    {
        $cosmetic = Cosmetic::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'rarity' => 'nullable|string',
            'type' => 'nullable|string',
            'series' => 'nullable|string',
            'set' => 'nullable|string',
            'introduction' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        $cosmetic->update($data);

        return response()->json([
            'message' => 'Cosmético atualizado com sucesso!',
            'data' => $cosmetic
        ]);
    }

    /**
     * Remove um cosmético do banco.
     */
    public function destroy($id)
    {
        $cosmetic = Cosmetic::findOrFail($id);
        $cosmetic->delete();

        return response()->json(['message' => 'Cosmético removido com sucesso!']);
    }
}
