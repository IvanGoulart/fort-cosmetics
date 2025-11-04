<div class="relative bg-white shadow rounded-lg overflow-hidden text-center transition hover:shadow-lg">
    {{-- Imagem --}}
    <img src="{{ $item->image }}" alt="{{ $item->name }}" class="w-full h-52 object-contain bg-gray-50">

    {{-- Selo "adquirido" --}}
    @auth
        @if(in_array($item->id, $ownedCosmetics))
            <div class="absolute top-2 right-2 bg-green-600 text-white text-xs font-semibold px-2 py-1 rounded">
                ✅ Adquirido
            </div>
        @endif
    @endauth

    {{-- Selos de status (novo, loja, promoção) --}}
    @if($item->is_new)
        <div class="absolute top-2 left-2 bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded shadow">
            🆕 Novo
        </div>
    @elseif($item->is_shop)
        <div class="absolute top-2 left-2 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded shadow">
            🛒 Loja
        </div>
    @elseif($item->regular_price && $item->price < $item->regular_price)
        <div class="absolute top-2 left-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded shadow">
            🔥 Promoção
        </div>
    @endif

    {{-- Conteúdo --}}
    <div class="p-4">
        <h5 class="font-semibold text-gray-800">{{ $item->name }}</h5>
        <p class="text-sm text-gray-500 mb-2">{{ $item->rarity ?? 'Comum' }}</p>
        <p class="text-indigo-700 font-bold mb-3">
            {{ $item->price }} V-Bucks
            @if($item->regular_price && $item->price < $item->regular_price)
                <span class="text-gray-400 line-through text-xs ml-2">{{ $item->regular_price }}</span>
            @endif
        </p>

        {{-- Botão de detalhes --}}
        <a href="{{ route('cosmetics.show', $item->id) }}"
           class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 rounded text-sm mb-2 transition">
            Ver detalhes
        </a>

        {{-- Botão de compra ou selo --}}
        @auth
            @if(in_array($item->id, $ownedCosmetics))
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
