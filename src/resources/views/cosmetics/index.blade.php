@extends('layouts.app')

@section('title', 'Cosméticos Fortnite')

@section('content')
<!-- Cabeçalho -->
<header class="bg-blue-700 text-white shadow-md fixed top-0 left-0 w-full z-50">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
        <h1 class="text-2xl font-bold tracking-wide">FortCosmetics 🎮</h1>
        <nav class="space-x-6 hidden md:block">
            <a href="{{ route('cosmetics.index') }}" class="hover:text-yellow-300 transition">Início</a>
            <a href="#" class="hover:text-yellow-300 transition">Sobre</a>
            <a href="#" class="hover:text-yellow-300 transition">Contato</a>
        </nav>
        <button class="md:hidden border border-white rounded p-1 hover:bg-blue-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>
</header>

<!-- Conteúdo principal (centralizado corretamente) -->
<main class="pt-28 pb-16 bg-gray-50 min-h-screen flex flex-col items-center">
    <!-- Ajuste chave: limite de largura com max-w-7xl e padding interno, e uso de mx-auto para centralizar -->
    <div class="w-full max-w-7xl px-4">
        <h2 class="text-4xl font-extrabold text-center text-gray-800 mb-10">Catálogo de Cosméticos</h2>

        <!-- Filtro centralizado e com largura controlada -->
        <form method="GET" action="{{ route('cosmetics.index') }}" 
              class="w-full max-w-5xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-10">
            <input 
                type="text" 
                name="nome" 
                value="{{ request('nome') }}" 
                class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Nome">

            <input 
                type="text" 
                name="tipo" 
                value="{{ request('tipo') }}" 
                class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Tipo">

            <input 
                type="text" 
                name="raridade" 
                value="{{ request('raridade') }}" 
                class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Raridade">

            <button 
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg px-4 py-2 transition-all duration-200">
                Filtrar
            </button>
        </form>

        <!-- Cards: chave = usar justify-center + cada card com largura fixa (max-w-sm / max-w-xs) -->
        @if ($cosmetics->count() > 0)
            <div class="w-full">
                <div class="grid justify-center gap-8 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @foreach ($cosmetics as $item)
                        <div class="w-full max-w-xs bg-white rounded-xl shadow hover:shadow-xl transition duration-300 overflow-hidden mx-auto">
                            <div class="w-full h-56 bg-gray-100 flex items-center justify-center overflow-hidden">
                                <img 
                                    src="{{ $item->image ?? 'https://via.placeholder.com/400x400?text=Sem+Imagem' }}" 
                                    alt="{{ $item->name }}" 
                                    class="max-h-full object-contain">
                            </div>

                            <div class="p-4 text-center">
                                <h6 class="text-lg font-semibold text-gray-800 truncate">{{ $item->name }}</h6>
                                <p class="text-sm text-gray-500">{{ $item->type }} • {{ $item->rarity }}</p>
                                <p class="mt-2 text-blue-600 font-bold">{{ $item->price }} V-Bucks</p>

                                <div class="mt-3 flex justify-center gap-2">
                                    @if ($item->is_new)
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full">Novo</span>
                                    @endif
                                    @if ($item->is_shop)
                                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">À venda</span>
                                    @endif
                                </div>

                                <a href="{{ route('cosmetics.show', $item->id) }}" 
                                   class="inline-block mt-4 bg-transparent border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white text-sm font-medium px-4 py-2 rounded-lg transition duration-200">
                                    Ver detalhes
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-lg shadow-md border border-dashed border-gray-300 max-w-3xl mx-auto">
                <p class="text-gray-600 text-lg">Nenhum cosmético encontrado 😢</p>
            </div>
        @endif

        <!-- Paginação -->
        <div class="mt-10 flex justify-center">
            {{ $cosmetics->links() }}
        </div>
    </div>
</main>
@endsection
