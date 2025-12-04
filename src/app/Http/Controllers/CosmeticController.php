<?php

namespace App\Http\Controllers;

use App\Services\CosmeticService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CosmeticController extends Controller
{
    protected CosmeticService $cosmeticService;

    public function __construct(CosmeticService $cosmeticService)
    {
        $this->cosmeticService = $cosmeticService;
    }

    public function index(Request $request)
    {
        $cosmetics = $this->cosmeticService->getFilteredCosmetics($request);

        $ownedCosmetics = auth()->check()
            ? auth()->user()->cosmetics()->pluck('cosmetic_id')->toArray()
            : [];

        return view('cosmetics.index', compact('cosmetics', 'ownedCosmetics'));
    }

    public function show(int $id, Request $request)
    {
        $cosmetic = \App\Models\Cosmetic::with('items')->findOrFail($id);

        $owned = false;
        $returned = false;

        if (Auth::check()) {
            $pivot = Auth::user()
                ->cosmetics()
                ->where('cosmetic_id', $id)
                ->first();

            if ($pivot) {
                $owned = ! $pivot->pivot->returned;
                $returned = $pivot->pivot->returned;
            }
        }

        // 🔹 Modo: padrão ou histórico
        $modo = $request->query('modo', 'padrao');

        return view('cosmetics.show', compact('cosmetic', 'owned', 'returned', 'modo'));
    }

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

        $cosmetic = $this->cosmeticService->createCosmetic($data);

        return response()->json([
            'message' => 'Cosmético criado com sucesso!',
            'data' => $cosmetic,
        ]);
    }

    public function update(Request $request, int $id)
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

        $cosmetic = $this->cosmeticService->updateCosmetic($id, $data);

        return response()->json([
            'message' => 'Cosmético atualizado com sucesso!',
            'data' => $cosmetic,
        ]);
    }

    public function destroy(int $id)
    {
        $this->cosmeticService->deleteCosmetic($id);

        return response()->json(['message' => 'Cosmético removido com sucesso!']);
    }
}
