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


    <div class="container mx-auto py-10 px-4">
    <h2 class="text-2xl font-bold mb-6 text-center">Catálogo de Cosméticos</h2>

    {{-- Barra de filtros --}}
    <div class="flex flex-wrap justify-center gap-3 mb-6">
        @php
            $filters = [
                'todos' => 'Todos',
                'novo' => '🆕 Novos',
                'promocao' => '🔥 Promoções',
                'bundle' => '🎁 Bundles',
                'loja' => '🛒 Loja'
            ];
        @endphp

        @foreach($filters as $key => $label)
            <a href="{{ route('cosmetics.index', ['filter' => $key !== 'todos' ? $key : null]) }}"
               class="px-4 py-2 rounded-full text-sm font-semibold transition
               {{ ($filter === $key || ($key === 'todos' && !$filter))
                    ? 'bg-indigo-600 text-white shadow'
                    : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Mensagens de sucesso/erro --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 text-sm p-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-800 text-sm p-2 rounded mb-4">{{ session('error') }}</div>
    @endif

    {{-- Grid de cosméticos --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($cosmetics as $item)
            @include('partials.cosmetic-card', ['item' => $item, 'ownedCosmetics' => $ownedCosmetics])
        @empty
            <p class="col-span-full text-center text-gray-500 py-6">Nenhum item encontrado neste filtro.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $cosmetics->appends(['filter' => $filter])->links() }}
    </div>
</div>