@extends('layouts.app')

@section('title', $cosmetic->name . ' - Detalhes')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden mt-10">
    
    {{-- 🖼️ Imagem destacada com fundo escuro e gradiente --}}
    <div class="relative w-full h-96 bg-gray-700 flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(255,255,255,0.15),rgba(0,0,0,0.5))]"></div>

        <img 
            src="{{ $cosmetic->image ?? asset('images/default.png') }}" 
            alt="{{ $cosmetic->name ?? 'Cosmético' }}" 
            class="max-h-full max-w-full object-contain relative z-10 drop-shadow-2xl transition-transform duration-300 hover:scale-105"
            loading="lazy"
        >
    </div>

    <div class="p-6">
        {{-- 🔖 Nome e informações básicas --}}
        <h2 class="text-3xl font-bold text-gray-800 mb-2 text-center">{{ $cosmetic->name }}</h2>
        <p class="text-center text-gray-500 text-sm mb-4">
            {{ ucfirst($cosmetic->rarity ?? 'common') }} • {{ ucfirst($cosmetic->type ?? 'item') }}
        </p>

        {{-- 🏷️ Etiquetas visuais --}}
        <div class="flex justify-center gap-2 mb-4">
            @if($cosmetic->is_new)
                <span class="bg-yellow-500 text-white text-xs font-semibold px-2 py-1 rounded shadow">🆕 Novo</span>
            @endif
            @if($cosmetic->is_shop)
                <span class="bg-blue-600 text-white text-xs font-semibold px-2 py-1 rounded shadow">🛒 Loja</span>
            @endif
            @if(isset($cosmetic->regular_price) && $cosmetic->price < $cosmetic->regular_price)
                <span class="bg-red-600 text-white text-xs font-semibold px-2 py-1 rounded shadow">🔥 Promoção</span>
            @endif
            @if($cosmetic->type === 'bundle')
                <span class="bg-purple-700 text-white text-xs font-semibold px-2 py-1 rounded shadow">🎁 Bundle</span>
            @endif
        </div>

        {{-- 💰 Preço --}}
        <div class="text-center mb-6">
            <p class="text-2xl font-bold text-indigo-700">
                {{ $cosmetic->price }} V-Bucks
                @if(isset($cosmetic->regular_price) && $cosmetic->price < $cosmetic->regular_price)
                    <span class="text-gray-400 line-through text-base ml-2">{{ $cosmetic->regular_price }}</span>
                @endif
            </p>
        </div>

        {{-- 🧩 Itens do bundle --}}
        @if($cosmetic->relationLoaded('items') && $cosmetic->items->isNotEmpty())
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2 text-center">Itens incluídos neste bundle:</h3>
                <ul class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm text-gray-700">
                    @foreach($cosmetic->items as $item)
                        <li class="p-2 bg-gray-100 rounded-md text-center shadow-sm hover:bg-gray-200 transition">
                            {{ $item->name }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 🛍️ Ações de compra / devolução --}}
        <div class="flex justify-center mt-6">
            @auth
                {{-- ✅ Item já comprado e ativo --}}
                @if($owned && !$returned)
                    @if($cosmetic->bundle_id)
                        {{-- 🔒 Item faz parte de um bundle --}}
                        <span class="bg-yellow-100 text-yellow-800 text-sm font-semibold px-4 py-2 rounded-lg shadow-sm">
                            ⚠️ Este item pertence a um bundle e não pode ser devolvido separadamente.
                        </span>
                    @else
                        {{-- 🧾 Item individual pode ser devolvido --}}
                        <form method="POST" action="{{ route('refund', $cosmetic->id) }}">
                            @csrf
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                                Devolver
                            </button>
                        </form>
                    @endif

                {{-- 🔄 Item devolvido --}}
                @elseif($returned)
                    <span class="bg-gray-200 text-gray-600 text-sm font-semibold px-4 py-2 rounded-full">
                        🔄 Item devolvido
                    </span>

                {{-- 🛒 Item ainda não comprado --}}
                @else
                    <form method="POST" action="{{ route('buy', ['id' => $cosmetic->id]) }}">
                        @csrf
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                            Comprar
                        </button>
                    </form>
                @endif
            @else
                <p class="text-gray-500 italic text-sm">Faça login para comprar este item.</p>
            @endauth
        </div>

        {{-- 🔙 Botão de voltar --}}
        <div class="text-center mt-8">
            <a href="{{ url()->previous() }}" 
               class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-5 py-2 rounded-md transition">
                🔙 Voltar
            </a>
        </div>
    </div>
</div>
@endsection
