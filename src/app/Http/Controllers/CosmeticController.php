<?php

namespace App\Http\Controllers;

use App\Models\Cosmetic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserCosmetic;

class CosmeticController extends Controller
{
    /**
     * Exibe uma lista de cosméticos.
     */

public function index(Request $request)
{
    $query = \App\Models\Cosmetic::query();

    // 🔍 Filtros existentes
    if ($request->filled('name')) {
        $query->where('name', 'LIKE', '%' . $request->name . '%');
    }

    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    if ($request->filled('rarity')) {
        $query->where('rarity', $request->rarity);
    }

    if ($request->boolean('is_new')) {
        $query->where('is_new', true);
    }

    if ($request->boolean('is_shop')) {
        $query->where('is_shop', true);
    }

    if ($request->boolean('on_sale')) {
        $query->whereColumn('price', '<', 'regular_price');
    }

    // 🎁 Novo filtro para Bundles
    if ($request->boolean('is_bundle')) {
        $query->where('type', 'bundle');
    }

    // 🔹 Carrega os itens relacionados (para bundles)
    $cosmetics = $query
        ->with('items')
        ->orderBy('name')
        ->paginate(12)
        ->appends($request->query());

    // 🔹 Cosméticos já adquiridos
    $ownedCosmetics = auth()->check()
        ? auth()->user()->cosmetics()->pluck('cosmetic_id')->toArray()
        : [];

    return view('cosmetics.index', compact('cosmetics', 'ownedCosmetics'));
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

        $owned = false;
        $returned = false;

        if (Auth::check()) {
            $pivot = Auth::user()
                ->cosmetics()
                ->where('cosmetic_id', $id)
                ->first();

            if ($pivot) {
                $owned = !$pivot->pivot->returned;
                $returned = $pivot->pivot->returned;
            }
        }

        return view('cosmetics.show', compact('cosmetic', 'owned', 'returned'));
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
