@php
    $modo = $modo ?? 'padrao'; // valores possíveis: 'padrao' ou 'historico'
@endphp
<div class="relative bg-white shadow rounded-lg text-center transition hover:shadow-lg overflow-visible">

    {{-- 🖼️ Imagem com fundo e contraste melhorado --}}
    <div class="relative w-full h-52 bg-gray-700 flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(255,255,255,0.15),rgba(0,0,0,0.4))]"></div>
        <img 
            src="{{ $item->image ?? asset('images/default.png') }}" 
            alt="{{ $item->name ?? 'Cosmético' }}" 
            class="max-h-full max-w-full object-contain relative z-10 drop-shadow-xl transition-transform duration-300 hover:scale-105"
            loading="lazy"
        >

        {{-- 🏷️ Etiquetas unificadas --}}
        <div class="absolute top-2 left-2 flex flex-col gap-1 z-20">
            @if($item->is_new)
                <span class="badge-tag bg-yellow-500">🆕 Novo</span>
            @endif

            @if($item->is_shop)
                <span class="badge-tag bg-blue-600">🛒 Loja</span>
            @endif

            @if(isset($item->regular_price) && $item->price < $item->regular_price)
                <span class="badge-tag bg-red-600">🔥 Promoção</span>
            @endif

            @if($item->type === 'bundle')
                <span class="badge-tag bg-purple-700">🎁 Bundle</span>
            @endif
        </div>

        {{-- ✅ Selo de adquirido (canto superior direito) --}}
        @auth
            @if(in_array($item->id, $ownedCosmetics))
                <div class="absolute top-2 right-2 bg-green-600 text-white text-xs font-semibold px-2 py-1 rounded z-20 shadow">
                    ✅ Adquirido
                </div>
            @endif
        @endauth
    </div>

    {{-- 📋 Conteúdo principal --}}
    <div class="p-4">
        <h5 class="font-semibold text-gray-800">{{ $item->name ?? 'Sem nome' }}</h5>
        <p class="text-sm text-gray-500 mb-2">{{ ucfirst($item->rarity ?? 'Comum') }}</p>

        {{-- 💰 Preço --}}
        <p class="text-indigo-700 font-bold mb-3">
            {{ $item->price }} V-Bucks
            @if(isset($item->regular_price) && $item->price < $item->regular_price)
                <span class="text-gray-400 line-through text-xs ml-2">{{ $item->regular_price }}</span>
            @endif
        </p>
        <a href="{{ route('cosmetics.show', $item->id) }}"
            class="inline-block w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 mb-3 rounded-md transition">
            Ver detalhes
            </a>

        {{-- 🧩 Tooltip de bundle --}}
        @if($item->relationLoaded('items') && $item->items->isNotEmpty())
            <div class="relative inline-block mb-3 group">
                <span class="text-indigo-600 text-sm underline cursor-pointer">Ver itens do bundle</span>

                <div class="absolute hidden group-hover:block bg-white border border-gray-200 rounded-lg shadow-lg p-3 w-56 left-1/2 -translate-x-1/2 z-50 text-left">
                    <h4 class="text-sm font-semibold mb-2 text-gray-700">Itens incluídos:</h4>
                    <ul class="text-xs text-gray-600 space-y-1 max-h-40 overflow-y-auto">
                        @foreach($item->items as $bundleItem)
                            <li>• {{ $bundleItem->name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- 🛍️ Botão de compra (somente no modo padrão) --}}
        @if($modo === 'padrao')
            @auth
                @if(in_array($item->id, $ownedCosmetics))
                    <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                        ✅ Já adquirido
                    </span>
                @else
                    <form method="POST" action="{{ route('buy', ['id' => $item->id]) }}">
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
        @endif

    </div>
</div>
