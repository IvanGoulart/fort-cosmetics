<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;

class TransactionController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        $transactions = $this->transactionService->getUserTransactions();

        return view('transactions.index', compact('transactions'));
    }
}
