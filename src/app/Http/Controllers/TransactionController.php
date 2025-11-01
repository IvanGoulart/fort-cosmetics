<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Cosmetic;


class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->with('cosmetic')
            ->orderByDesc('executed_at')
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

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

        // Marca como comprado
        $user->cosmetics()->attach($id, ['returned' => false]);

        // 🔥 Grava transação
        Transaction::create([
            'user_id' => $user->id,
            'cosmetic_id' => $cosmetic->id,
            'type' => 'compra',
            'amount' => $cosmetic->price,
            'executed_at' => now(),
        ]);

        return back()->with('success', 'Item comprado com sucesso!');
    }

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

        // 🔥 Grava transação
        Transaction::create([
            'user_id' => $user->id,
            'cosmetic_id' => $cosmetic->id,
            'type' => 'devolucao',
            'amount' => $cosmetic->price,
            'executed_at' => now(),
        ]);

        return back()->with('success', 'Item devolvido e créditos reembolsados!');
    }

}
