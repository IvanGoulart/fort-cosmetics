<?php

namespace App\Services;

use App\Models\Cosmetic;
use Illuminate\Http\Request;

class CosmeticService
{
    /**
     * Retorna uma lista paginada de cosméticos com filtros aplicados
     */
    public function getFilteredCosmetics(Request $request)
    {
        $query = Cosmetic::query();

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

        if ($request->boolean('is_bundle')) {
            $query->where('type', 'bundle');
        }

        // 🔹 Filtro por intervalo de datas
        if ($request->filled('date_start') && $request->filled('date_end')) {
            $query->whereBetween('created_at', [
                $request->date_start . ' 00:00:00',
                $request->date_end . ' 23:59:59',
            ]);
        } elseif ($request->filled('date_start')) {
            $query->whereDate('created_at', '>=', $request->date_start);
        } elseif ($request->filled('date_end')) {
            $query->whereDate('created_at', '<=', $request->date_end);
        }

        return $query
            ->with('items')
            ->orderBy('name')
            ->paginate(12)
            ->appends($request->query());
    }

    /**
     * Obtém cosmético por ID
     */
    public function getCosmeticById(int $id): Cosmetic
    {
        return Cosmetic::findOrFail($id);
    }

    /**
     * Cria um novo cosmético
     */
    public function createCosmetic(array $data): Cosmetic
    {
        return Cosmetic::create($data);
    }

    /**
     * Atualiza um cosmético existente
     */
    public function updateCosmetic(int $id, array $data): Cosmetic
    {
        $cosmetic = Cosmetic::findOrFail($id);
        $cosmetic->update($data);
        return $cosmetic;
    }

    /**
     * Remove um cosmético
     */
    public function deleteCosmetic(int $id): void
    {
        $cosmetic = Cosmetic::findOrFail($id);
        $cosmetic->delete();
    }
}
