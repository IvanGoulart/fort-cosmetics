@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10 px-4">
    <div class="bg-white shadow-xl rounded-lg p-8 max-w-3xl mx-auto">
        {{-- Imagem principal --}}
        <div class="flex flex-col items-center">
            <img src="{{ $cosmetic->image }}" alt="{{ $cosmetic->name }}" 
                 class="w-64 h-64 object-contain mb-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">{{ $cosmetic->name }}</h2>
            <p class="text-gray-500 text-sm mb-4 italic">{{ $cosmetic->description ?? 'Sem descrição disponível.' }}</p>

            {{-- Status / Ícones --}}
            <div class="flex flex-wrap justify-center gap-2 mb-6">
                @if($cosmetic->is_new)
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 text-sm font-semibold rounded-full">
                        ✨ Novo
                    </span>
                @endif
                @if($cosmetic->is_shop)
                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 text-sm font-semibold rounded-full">
                        🛒 À venda
                    </span>
                @endif
                @if($owned)
                    <span class="bg-green-100 text-green-700 px-3 py-1 text-sm font-semibold rounded-full">
                        ✅ Adquirido
                    </span>
                @elseif($returned)
                    <span class="bg-gray-100 text-gray-600 px-3 py-1 text-sm font-semibold rounded-full">
                        🔁 Devolvido
                    </span>
                @endif
            </div>

            {{-- Preço --}}
            <p class="text-indigo-700 text-xl font-bold mb-4">
                {{ $cosmetic->price }} V-Bucks
            </p>

            {{-- Botões de ação --}}
            @auth
                @if($owned)
                    <form method="POST" action="{{ route('refund', $cosmetic->id) }}">
                        @csrf
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                            Devolver
                        </button>
                    </form>
                @elseif($returned)
                    <span class="text-gray-500 italic">Você já devolveu este item.</span>
                @else
                    <form method="POST" action="{{ route('buy', $cosmetic->id) }}">
                        @csrf
                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                            Comprar agora
                        </button>
                    </form>
                @endif
            @else
                <p class="text-gray-500 mt-2 italic">Faça login para comprar este item.</p>
            @endauth

            {{-- Link voltar --}}
            <a href="{{ route('cosmetics.index') }}"
               class="block mt-8 text-indigo-600 hover:text-indigo-800 font-semibold">
               ← Voltar para a lista
            </a>
        </div>
    </div>
</div>
@endsection
