@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10 px-4">
    <h2 class="text-2xl font-bold mb-6 text-center">🎒 Meus Cosméticos</h2>

    {{-- Mensagens --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 text-sm p-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-800 text-sm p-2 rounded mb-4">{{ session('error') }}</div>
    @endif

    @if($purchases->isEmpty())
        <p class="text-center text-gray-600 mt-10">Você ainda não comprou nenhum cosmético.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($purchases as $item)
                <div class="relative bg-white shadow rounded-lg text-center transition hover:shadow-lg overflow-visible">
                    
                    {{-- 🖼️ Imagem com fundo escuro --}}
                    <div class="relative w-full h-52 bg-gray-700 flex items-center justify-center overflow-hidden">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(255,255,255,0.15),rgba(0,0,0,0.4))]"></div>
                        <img 
                            src="{{ $item->image ?? asset('images/default.png') }}" 
                            alt="{{ $item->name }}" 
                            class="max-h-full max-w-full object-contain relative z-10 drop-shadow-xl transition-transform duration-300 hover:scale-105"
                            loading="lazy"
                        >

                        {{-- Selo adquirido --}}
                        <div class="absolute top-2 right-2 bg-green-600 text-white text-xs font-semibold px-2 py-1 rounded z-20 shadow">
                            ✅ Adquirido
                        </div>

                        {{-- Etiquetas --}}
                        <div class="absolute top-2 left-2 flex flex-col gap-1 z-20">
                            @if($item->is_new)
                                <span class="badge-tag bg-yellow-500">🆕 Novo</span>
                            @endif
                            @if($item->is_shop)
                                <span class="badge-tag bg-blue-600">🛒 Loja</span>
                            @endif
                            @if($item->type === 'bundle')
                                <span class="badge-tag bg-purple-700">🎁 Bundle</span>
                            @endif
                        </div>
                    </div>

                    {{-- Conteúdo --}}
                    <div class="p-4">
                        <h5 class="font-semibold text-gray-800">{{ $item->name }}</h5>
                        <p class="text-sm text-gray-500 mb-2">{{ ucfirst($item->rarity ?? 'Comum') }}</p>
                        <p class="text-indigo-700 font-bold mb-3">{{ $item->price }} V-Bucks</p>

                        {{-- Tooltip de bundle (se for um bundle com itens) --}}
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

                        {{-- Botão Detalhes --}}
                        <a href="{{ route('cosmetics.show', $item->id) }}"
                           class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 rounded text-sm mb-2 transition">
                           Ver detalhes
                        </a>

                        {{-- Devolução ou aviso de bundle --}}
                        @if($item->bundle_id)
                            {{-- ⚠️ Mensagem: pertence a um bundle --}}
                            <div class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-2 rounded-md shadow-sm">
                                ⚠️ Este item pertence ao bundle 
                                <strong>{{ $item->bundle->name ?? 'Desconhecido' }}</strong> 
                                e não pode ser devolvido separadamente.
                            </div>
                        @else
                            {{-- 🧾 Botão de devolução normal --}}
                            <form method="POST" action="{{ route('refund', $item->id) }}">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded text-sm font-semibold transition mt-2">
                                    Devolver
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
