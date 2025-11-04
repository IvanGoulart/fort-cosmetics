<?php

namespace App\Http\Controllers;

use App\Models\Cosmetic;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    /**
     * Exibe os cosméticos que o usuário possui
     */

    public function index()
    {
        $user = Auth::user();

        $purchases = $user->cosmetics()
            ->wherePivot('returned', false)
            ->get();

        return view('shop.my_cosmetics', compact('purchases'));
    }

    /**
     * Comprar um cosmético
     */
public function buy($id)
{
    $user = Auth::user();
    $cosmetic = Cosmetic::findOrFail($id);

    // Verifica se já possui o item (ou o bundle)
    if ($user->cosmetics()->where('cosmetic_id', $id)->where('returned', false)->exists()) {
        return back()->with('error', 'Você já possui este item!');
    }

    // Verifica saldo
    if ($user->vbucks < $cosmetic->price) {
        return back()->with('error', 'Créditos insuficientes.');
    }

    // Debita o valor
    $user->vbucks -= $cosmetic->price;
    $user->save();

    // Registra a transação
    Transaction::create([
        'user_id' => $user->id,
        'cosmetic_id' => $cosmetic->id,
        'type' => 'compra',
        'amount' => $cosmetic->price,
        'executed_at' => now(),
    ]);

    // --- 🎁 Caso o item seja um BUNDLE ---
    if ($cosmetic->type === 'bundle') {
        // Marca o bundle como adquirido
        $user->cosmetics()->attach($cosmetic->id, ['returned' => false]);

        // Busca todos os itens dentro do bundle
        $bundleItems = Cosmetic::where('bundle_id', $cosmetic->id)->get();

        foreach ($bundleItems as $item) {
            // Evita duplicação caso o usuário já tenha algum item do bundle
            if (!$user->cosmetics()->where('cosmetic_id', $item->id)->where('returned', false)->exists()) {
                $user->cosmetics()->attach($item->id, ['returned' => false]);
            }
        }

        return back()->with('success', "Bundle '{$cosmetic->name}' e seus itens foram comprados com sucesso!");
    }

    // --- 💎 Caso seja um item individual ---
    $user->cosmetics()->attach($cosmetic->id, ['returned' => false]);

    return back()->with('success', 'Item comprado com sucesso!');
}


    /**
     * Devolver um cosmético
     */
public function refund($id)
{
    $user = Auth::user();
    $cosmetic = Cosmetic::findOrFail($id);

    // Verifica se o usuário realmente possui o item e se ainda não foi devolvido
    $pivot = $user->cosmetics()
        ->where('cosmetic_id', $id)
        ->where('returned', false)
        ->first();

    if (!$pivot) {
        return back()->with('error', 'Este item não está na sua coleção ou já foi devolvido.');
    }

    // --- 🎁 Caso seja um BUNDLE ---
    if ($cosmetic->type === 'bundle') {
        // Marca o bundle como devolvido
        $user->cosmetics()->updateExistingPivot($cosmetic->id, ['returned' => true]);

        // Busca e marca todos os itens que pertencem ao bundle
        $bundleItems = Cosmetic::where('bundle_id', $cosmetic->id)->get();

        foreach ($bundleItems as $item) {
            $user->cosmetics()
                ->wherePivot('returned', false)
                ->updateExistingPivot($item->id, ['returned' => true]);
        }

        // Reembolsa o valor total do bundle
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

        return back()->with('success', "Bundle '{$cosmetic->name}' e seus itens foram devolvidos!");
    }

    // --- 💎 Caso seja um item individual ---
    if ($cosmetic->bundle_id) {
        // Item faz parte de um bundle — não permitir devolução individual
        return back()->with('error', 'Este item pertence a um bundle e não pode ser devolvido separadamente.');
    }

    // Marca devolução do item individual
    $user->cosmetics()->updateExistingPivot($id, ['returned' => true]);

    // Credita novamente o valor do item
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

    return back()->with('success', 'Item devolvido e créditos reembolsados!');
}

}
