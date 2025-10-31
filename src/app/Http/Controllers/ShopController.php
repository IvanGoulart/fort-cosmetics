<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cosmetic;
use App\Models\UserCosmetic;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    /**
     * Exibe os cosméticos que o usuário possui
     */
    public function index()
    {
        $purchases = Auth::user()
            ->cosmetics()
            ->withPivot('returned', 'created_at')
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

        return back()->with('success', 'Item devolvido e créditos reembolsados!');
    }
}
