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

        Transaction::create([
            'user_id' => $user->id,
            'cosmetic_id' => $cosmetic->id,
            'type' => 'compra',
            'amount' => $cosmetic->price,
            'executed_at' => now(),
        ]);

        // Já possui?
        if ($user->cosmetics()->where('cosmetic_id', $id)->where('returned', false)->exists()) {
            return back()->with('error', 'Você já possui este item!');
        }

        // Saldo suficiente?
        if ($user->vbucks < $cosmetic->price) {
            return back()->with('error', 'Créditos insuficientes.');
        }

        // Debita e registra
        $user->vbucks -= $cosmetic->price;
        $user->save();

        $user->cosmetics()->attach($id, ['returned' => false]);

        return back()->with('success', 'Item comprado com sucesso!');
    }

    /**
     * Devolver um cosmético
     */
    public function refund($id)
    {
        $user = Auth::user();
        $cosmetic = Cosmetic::findOrFail($id);

        $pivot = $user->cosmetics()->where('cosmetic_id', $id)->where('returned', false)->first();

        if (!$pivot) {
            return back()->with('error', 'Este item não está na sua coleção ou já foi devolvido.');
        }

        // Marca devolução
        $user->cosmetics()->updateExistingPivot($id, ['returned' => true]);

        // Credita novamente
        $user->vbucks += $cosmetic->price;
        $user->save();

        Transaction::create([
            'user_id' => $user->id,
            'cosmetic_id' => $cosmetic->id,
            'type' => 'devolução',
            'amount' => $cosmetic->price,
            'executed_at' => now(),
        ]);

        return back()->with('success', 'Item devolvido e créditos reembolsados!');
    }
}
