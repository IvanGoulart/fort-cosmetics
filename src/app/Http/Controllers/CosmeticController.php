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

public function index()
{
    $query = \App\Models\Cosmetic::query();

    // Filtro por tipo
    $filter = request('filter');

    switch ($filter) {
        case 'novo':
            $query->where('is_new', true);
            break;

        case 'promocao':
            $query->whereColumn('price', '<', 'regular_price');
            break;

        case 'bundle':
            $query->where('type', 'bundle');
            break;

        case 'loja':
            $query->where('is_shop', true);
            break;
    }

    // Ordenação padrão (nome)
    $cosmetics = $query->orderBy('name')->paginate(12);

    // Cosméticos já adquiridos
    $ownedCosmetics = auth()->check()
        ? auth()->user()->cosmetics()->pluck('cosmetic_id')->toArray()
        : [];

    return view('cosmetics.index', compact('cosmetics', 'ownedCosmetics', 'filter'));
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
