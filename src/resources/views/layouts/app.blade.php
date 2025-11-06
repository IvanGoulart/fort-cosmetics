<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FortCosmetics 🎮')</title>

    {{-- CSS e JS gerados pelo Vite (Tailwind + scripts) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900 flex flex-col min-h-screen">

    {{-- 🔹 Navbar fixa --}}
    <nav class="bg-blue-700 text-white shadow-md fixed top-0 left-0 w-full z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">

            {{-- Logo --}}
            <a href="{{ route('cosmetics.index') }}" 
               class="text-2xl font-bold tracking-wide hover:text-yellow-300 transition">
                FortCosmetics 🎮
            </a>

            {{-- Links de navegação --}}
            <ul class="flex items-center gap-6">
                <li><a href="{{ route('cosmetics.index') }}" class="hover:text-yellow-300 transition">Início</a></li>

                @auth
                    <li><a href="{{ route('my.cosmetics') }}" class="hover:text-yellow-300 transition">🎒 Meus Cosméticos</a></li>
                    <li><a href="{{ route('transactions.index') }}" class="hover:text-yellow-300 transition">📜 Histórico</a></li>
                @endauth

                <li><a href="#" class="hover:text-yellow-300 transition">Sobre</a></li>
                <li><a href="{{ route('users.index') }}" class="hover:text-yellow-300 transition">👥 Comunidade</a></li>

            </ul>

            {{-- Ações de usuário --}}
            <div class="flex items-center gap-4">
                @auth
                    {{-- Saldo --}}
                    <div class="flex items-center gap-2 bg-blue-600 px-3 py-2 rounded-lg shadow-md">
                        <span class="font-medium">
                            💰 {{ auth()->user()->vbucks ?? 0 }} V-Bucks
                        </span>
                    </div>

                    {{-- Nome do usuário --}}
                    <span class="hidden sm:inline font-semibold">
                        Olá, {{ Auth::user()->name ?? 'Usuário' }}
                    </span>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}" 
                          onsubmit="return confirm('Deseja realmente sair?')" class="inline">
                        @csrf
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                            Sair
                        </button>
                    </form>
                @else
                    {{-- Login --}}
                    <a href="{{ route('login') }}"
                       class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Entrar
                    </a>
                @endauth
            </div>
        </div>

        {{-- 🔹 Barra de filtros rápida (fixa abaixo da navbar) --}}
{{-- 🔹 Barra de filtros moderna --}}
@if(request()->routeIs('cosmetics.index'))
    <div class="bg-gray-100 text-gray-800 px-6 py-4 border-t border-b border-blue-200 shadow-sm backdrop-blur">
        <form method="GET" action="{{ route('cosmetics.index') }}" 
              class="max-w-7xl mx-auto flex flex-wrap gap-3 items-center justify-between">

            {{-- Campo de busca --}}
            <div class="flex items-center gap-2">
                <input type="text" name="name" value="{{ request('name') }}" 
                       placeholder="🔍 Buscar por nome..."
                       class="w-60 md:w-80 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 outline-none transition">
            </div>

            {{-- Tipo e raridade --}}
            <div class="flex flex-wrap gap-2">
                <select name="type" class="filter-select">
                    <option value="">Tipo</option>
                    <option value="outfit" @selected(request('type')==='outfit')>Outfit</option>
                    <option value="backpack" @selected(request('type')==='backpack')>Backpack</option>
                    <option value="pickaxe" @selected(request('type')==='pickaxe')>Pickaxe</option>
                </select>

                <select name="rarity" class="filter-select">
                    <option value="">Raridade</option>
                    <option value="common" @selected(request('rarity')==='common')>Common</option>
                    <option value="uncommon" @selected(request('rarity')==='uncommon')>Uncommon</option>
                    <option value="rare" @selected(request('rarity')==='rare')>Rare</option>
                    <option value="epic" @selected(request('rarity')==='epic')>Epic</option>
                    <option value="legendary" @selected(request('rarity')==='legendary')>Legendary</option>
                </select>
            </div>

            {{-- Checkboxes --}}
            <div class="flex flex-wrap items-center gap-4">
                <label class="flex items-center gap-1 text-sm font-medium">
                    <input type="checkbox" name="is_new" value="1" @checked(request('is_new')) class="checkbox-filter">
                    <span>Novo</span>
                </label>
                <label class="flex items-center gap-1 text-sm font-medium">
                    <input type="checkbox" name="is_shop" value="1" @checked(request('is_shop')) class="checkbox-filter">
                    <span>Loja</span>
                </label>
                <label class="flex items-center gap-1 text-sm font-medium">
                    <input type="checkbox" name="on_sale" value="1" @checked(request('on_sale')) class="checkbox-filter">
                    <span>Promoção</span>
                </label>
                <label class="flex items-center gap-1 text-sm font-medium">
                    <input type="checkbox" name="is_bundle" value="1" @checked(request('is_bundle')) class="checkbox-filter">
                    <span>Bundles</span>
                </label>
            </div>

            {{-- Botões --}}
            <div class="flex items-center gap-2">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg shadow transition">
                    Filtrar
                </button>
                <a href="{{ route('cosmetics.index') }}"
                   class="text-blue-600 hover:text-blue-800 underline text-sm font-medium transition">
                    Limpar
                </a>
            </div>
        </form>
    </div>
@endif

    </nav>

    {{-- 🔹 Conteúdo principal (com compensação do header fixo) --}}
    <main class="flex-1 pt-[160px] pb-10">
        @yield('content')
    </main>

    {{-- 🔹 Rodapé --}}
    <footer class="bg-blue-700 text-white py-6 text-center mt-auto">
        <p class="text-sm">&copy; {{ date('Y') }} FortCosmetics - Todos os direitos reservados.</p>
        <p class="text-xs text-blue-200 mt-1">Desenvolvido por Ivan Goulart 💻</p>
    </footer>

</body>
</html>
