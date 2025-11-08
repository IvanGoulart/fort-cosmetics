<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class TransactionService
{
    /**
     * Retorna as transações do usuário autenticado.
     */
    public function getUserTransactions(int $perPage = 10): LengthAwarePaginator
    {
        return Transaction::where('user_id', Auth::id())
            ->with('cosmetic')
            ->orderByDesc('executed_at')
            ->paginate($perPage);
    }
}
