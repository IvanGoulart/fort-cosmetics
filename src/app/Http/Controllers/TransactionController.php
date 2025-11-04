<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

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
}
