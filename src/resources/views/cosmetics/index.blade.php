@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10 px-4">
    <h2 class="text-2xl font-bold mb-6 text-center">Catálogo de Cosméticos</h2>

    {{-- mensagens de sucesso/erro --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 text-sm p-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-800 text-sm p-2 rounded mb-4">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($cosmetics as $item)
<div class="relative bg-white shadow rounded-lg overflow-hidden text-center transition hover:shadow-lg">
    {{-- Imagem --}}
    <img src="{{ $item->image ?? asset('images/placeholder.png') }}" 
         alt="{{ $item->name }}" 
         class="w-full h-52 object-contain bg-gray-50">

    {{-- Selo "adquirido" --}}
    @auth
        @if(!empty($ownedCosmetics) && in_array($item->id, $ownedCosmetics))
            <div class="absolute top-2 right-2 bg-green-600 text-white text-xs font-semibold px-2 py-1 rounded shadow">
                ✅ Adquirido
            </div>
        @endif
    @endauth

    {{-- Selos de status (Novo, Loja, Promoção) --}}
    <div class="absolute top-2 left-2 flex flex-col items-start gap-1">
        @if($item->is_new)
            <span class="bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded shadow">
                🆕 Novo
            </span>
        @endif

        @if($item->is_shop)
            <span class="bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded shadow">
                🛒 Loja
            </span>
        @endif

        @if(isset($item->regular_price) && $item->price < $item->regular_price)
            <span class="bg-red-600 text-white text-xs font-bold px-2 py-1 rounded shadow">
                🔥 Promoção
            </span>
        @endif
    </div>

    {{-- Conteúdo --}}
    <div class="p-4">
        <h5 class="font-semibold text-gray-800 truncate" title="{{ $item->name }}">
            {{ $item->name }}
        </h5>
        <p class="text-sm text-gray-500 mb-2 capitalize">
            {{ $item->rarity ?? 'Sem raridade' }}
        </p>
        <p class="text-indigo-700 font-bold mb-3">
            {{ number_format($item->price ?? 0, 0, ',', '.') }} V-Bucks
        </p>

        {{-- Botão de detalhes --}}
        <a href="{{ route('cosmetics.show', $item->id) }}"
           class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 rounded text-sm mb-2 transition">
            Ver detalhes
        </a>

        {{-- Botão de compra / selo --}}
        @auth
            @if(!empty($ownedCosmetics) && in_array($item->id, $ownedCosmetics))
                <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                    ✅ Já adquirido
                </span>
            @else
                <form method="POST" action="{{ route('buy', $item->id) }}">
                    @csrf
                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded text-sm font-semibold transition">
                        Comprar
                    </button>
                </form>
            @endif
        @else
            <p class="text-sm text-gray-400 italic">Faça login para comprar</p>
        @endauth
    </div>
</div>

        @endforeach
    </div>

    <div class="mt-6">
        {{ $cosmetics->links() }}
    </div>
</div>
@endsection
