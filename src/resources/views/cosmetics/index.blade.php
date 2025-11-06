@extends('layouts.app')

@section('title', 'Catálogo de Cosméticos 🎮')

@section('content')
<div class="container mx-auto py-10 px-4">

    {{-- 🏷️ Título --}}
    <h2 class="text-2xl font-bold mb-6 text-center text-blue-700">
        Catálogo de Cosméticos
    </h2>

    {{-- 🔔 Mensagens de sucesso/erro --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 text-sm p-2 rounded mb-4 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-800 text-sm p-2 rounded mb-4 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- 🎨 Grid de cosméticos --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($cosmetics as $item)
            {{-- Inclui o card individual --}}
            @include('partials.cosmetic-card', [
                'item' => $item,
                'ownedCosmetics' => $ownedCosmetics ?? []
            ])
        @empty
            <p class="col-span-full text-center text-gray-500 py-6">
                Nenhum item encontrado neste filtro.
            </p>
        @endforelse
    </div>

    {{-- 📄 Paginação --}}
    @if($cosmetics->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $cosmetics->appends(request()->query())->links() }}
        </div>
    @endif

</div>
@endsection
