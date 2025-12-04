<?php

namespace App\Http\Controllers;

use App\Services\ShopService;
use Illuminate\Http\RedirectResponse;

class ShopController extends Controller
{
    protected ShopService $shopService;

    public function __construct(ShopService $shopService)
    {
        $this->shopService = $shopService;
    }

    public function index()
    {
        $purchases = $this->shopService->getUserPurchases();

        return view('shop.my_cosmetics', compact('purchases'));
    }

    public function buy(int $id): RedirectResponse
    {
        $message = $this->shopService->buyCosmetic($id);

        if (str_contains($message, 'sucesso')) {
            return back()->with('success', $message);
        }

        return back()->with('error', $message);
    }

    public function refund(int $id): RedirectResponse
    {
        $message = $this->shopService->refundCosmetic($id);

        if (str_contains($message, 'devolvido') || str_contains($message, 'sucesso')) {
            return back()->with('success', $message);
        }

        return back()->with('error', $message);
    }
}
