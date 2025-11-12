<?php

namespace App\Services;

use App\Models\Cosmetic;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class ShopService
{
    /**
     * Retorna todos os cosméticos comprados pelo usuário autenticado.
     */
    public function getUserPurchases(): Collection
    {
        $user = Auth::user();

        return $user->cosmetics()
            ->wherePivot('returned', false)
            ->get();
    }

    /**
     * Realiza a compra de um cosmético (bundle ou item individual).
     */
    public function buyCosmetic(int $cosmeticId): string
    {
        $user = Auth::user();
        $cosmetic = Cosmetic::findOrFail($cosmeticId);

        // Já possui
        if ($user->cosmetics()->where('cosmetic_id', $cosmeticId)->where('returned', false)->exists()) {
            return 'Você já possui este item!';
        }

        // Saldo insuficiente
        if ($user->vbucks < $cosmetic->price) {
            return 'Créditos insuficientes.';
        }

        // Debita o valor
        $user->vbucks -= $cosmetic->price;
        $user->save();

        // Registra transação
        Transaction::create([
            'user_id' => $user->id,
            'cosmetic_id' => $cosmetic->id,
            'type' => 'compra',
            'amount' => $cosmetic->price,
            'executed_at' => now(),
        ]);

        // --- 🎁 Se for BUNDLE ---
        if ($cosmetic->type === 'bundle') {
            $user->cosmetics()->attach($cosmetic->id, ['returned' => false]);

            $bundleItems = Cosmetic::where('bundle_id', $cosmetic->id)->get();

            foreach ($bundleItems as $item) {
                if (!$user->cosmetics()->where('cosmetic_id', $item->id)->where('returned', false)->exists()) {
                    $user->cosmetics()->attach($item->id, ['returned' => false]);
                }
            }

            return "Bundle '{$cosmetic->name}' e seus itens foram comprados com sucesso!";
        }

        // 💎 Item individual
        $user->cosmetics()->attach($cosmetic->id, ['returned' => false]);
        return 'Item comprado com sucesso!';
    }

    /**
     * Realiza devolução de um cosmético.
     */
    public function refundCosmetic(int $cosmeticId): string
    {
        $user = Auth::user();
        $cosmetic = Cosmetic::findOrFail($cosmeticId);

        $pivot = $user->cosmetics()
            ->where('cosmetic_id', $cosmeticId)
            ->where('returned', false)
            ->first();

        if (!$pivot) {
            return 'Este item não está na sua coleção ou já foi devolvido.';
        }

        // 🎁 Bundle
        if ($cosmetic->type === 'bundle') {
            $user->cosmetics()->updateExistingPivot($cosmetic->id, ['returned' => true]);

            $bundleItems = Cosmetic::where('bundle_id', $cosmetic->id)->get();

            foreach ($bundleItems as $item) {
                $user->cosmetics()->updateExistingPivot($item->id, ['returned' => true]);
            }

            $user->vbucks += $cosmetic->price;
            $user->save();

            Transaction::create([
                'user_id' => $user->id,
                'cosmetic_id' => $cosmetic->id,
                'type' => 'devolução',
                'amount' => $cosmetic->price,
                'executed_at' => now(),
                'details' => 'Devolução do bundle completo e seus itens.',
            ]);

            return "Bundle '{$cosmetic->name}' e seus itens foram devolvidos!";
        }

        // 💎 Item individual
        if ($cosmetic->bundle_id) {
            return 'Este item pertence a um bundle e não pode ser devolvido separadamente.';
        }

        $user->cosmetics()->updateExistingPivot($cosmeticId, ['returned' => true]);
        $user->vbucks += $cosmetic->price;
        $user->save();

        Transaction::create([
            'user_id' => $user->id,
            'cosmetic_id' => $cosmetic->id,
            'type' => 'devolução',
            'amount' => $cosmetic->price,
            'executed_at' => now(),
            'details' => 'Devolução de item individual.',
        ]);

        return 'Item devolvido e créditos reembolsados!';
    }
}
