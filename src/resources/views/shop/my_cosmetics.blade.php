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
                <div class="relative bg-white shadow rounded-lg overflow-hidden text-center transition hover:shadow-lg">
                    <img src="{{ $item->image }}" alt="{{ $item->name }}" class="w-full h-52 object-contain bg-gray-50">

                    {{-- Selo "Adquirido" --}}
                    <div class="absolute top-2 right-2 bg-green-600 text-white text-xs font-semibold px-2 py-1 rounded">
                        ✅ Adquirido
                    </div>

                    <div class="p-4">
                        <h5 class="font-semibold text-gray-800">{{ $item->name }}</h5>
                        <p class="text-sm text-gray-500 mb-2">{{ $item->rarity }}</p>
                        <p class="text-indigo-700 font-bold mb-3">{{ $item->price }} V-Bucks</p>

                        {{-- Botão de detalhes --}}
                        <a href="{{ route('cosmetics.show', $item->id) }}"
                           class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 rounded text-sm mb-2 transition">
                           Ver detalhes
                        </a>

                        {{-- Botão de devolução --}}
                        <form method="POST" action="{{ route('refund', $item->id) }}">
                            @csrf
                            <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded text-sm font-semibold transition">
                                Devolver
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
