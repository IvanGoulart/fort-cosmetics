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
                <img src="{{ $item->image }}" alt="{{ $item->name }}" class="w-full h-52 object-contain bg-gray-50">

                {{-- Selo "adquirido" --}}
                @auth
                    @if(in_array($item->id, $ownedCosmetics))
                        <div class="absolute top-2 right-2 bg-green-600 text-white text-xs font-semibold px-2 py-1 rounded">
                            ✅ Adquirido
                        </div>
                    @endif
                @endauth

                {{-- Conteúdo --}}
                <div class="p-4">
                    <h5 class="font-semibold text-gray-800">{{ $item->name }}</h5>
                    <p class="text-sm text-gray-500 mb-2">{{ $item->rarity }}</p>
                    <p class="text-indigo-700 font-bold mb-3">{{ $item->price }} V-Bucks</p>

                    {{-- Botão de detalhes (sempre visível) --}}
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
        @endforeach
    </div>

    <div class="mt-6">
        {{ $cosmetics->links() }}
    </div>
</div>
@endsection
