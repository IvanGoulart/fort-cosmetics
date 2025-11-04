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
            <a href="{{ route('cosmetics.index') }}" class="text-2xl font-bold tracking-wide hover:text-yellow-300 transition">
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
            </ul>

            {{-- Ações de usuário --}}
            <div class="flex items-center gap-4">
                @auth
                    {{-- Saldo --}}
                    <div class="flex items-center gap-2 bg-blue-600 px-3 py-2 rounded-lg shadow-md">
                        <span class="font-medium">💰 {{ auth()->user()->vbucks ?? 0 }} V-Bucks</span>
                    </div>

                    {{-- Nome do usuário --}}
                    <span class="hidden sm:inline font-semibold">Olá, {{ Auth::user()->name ?? 'Usuário' }}</span>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Deseja realmente sair?')" class="inline">
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
        @if(request()->routeIs('cosmetics.index'))
        <div class="bg-blue-600 text-white px-6 py-3 text-sm shadow-inner">
            <form method="GET" action="{{ route('cosmetics.index') }}" class="max-w-7xl mx-auto flex flex-wrap gap-2 items-center justify-between">
                {{-- Campo de busca --}}
                <input type="text" name="name" value="{{ request('name') }}" placeholder="Buscar por nome..."
                    class="w-52 md:w-72 rounded-md px-3 py-1.5 text-sm text-gray-900 focus:ring-2 focus:ring-yellow-300">

                {{-- Tipo --}}
                <select name="type" class="rounded-md text-gray-800 px-2 py-1">
                    <option value="">Tipo</option>
                    <option value="outfit" @selected(request('type')==='outfit')>Outfit</option>
                    <option value="backpack" @selected(request('type')==='backpack')>Backpack</option>
                    <option value="pickaxe" @selected(request('type')==='pickaxe')>Pickaxe</option>
                </select>

                {{-- Raridade --}}
                <select name="rarity" class="rounded-md text-gray-800 px-2 py-1">
                    <option value="">Raridade</option>
                    <option value="common" @selected(request('rarity')==='common')>Common</option>
                    <option value="uncommon" @selected(request('rarity')==='uncommon')>Uncommon</option>
                    <option value="rare" @selected(request('rarity')==='rare')>Rare</option>
                    <option value="epic" @selected(request('rarity')==='epic')>Epic</option>
                    <option value="legendary" @selected(request('rarity')==='legendary')>Legendary</option>
                </select>

                {{-- Novos / Loja / Promoção --}}
                <div class="flex items-center gap-3">
                    <label class="flex items-center gap-1">
                        <input type="checkbox" name="is_new" value="1" @checked(request('is_new')) class="rounded">
                        <span>Novo</span>
                    </label>
                    <label class="flex items-center gap-1">
                        <input type="checkbox" name="is_shop" value="1" @checked(request('is_shop')) class="rounded">
                        <span>Loja</span>
                    </label>
                    <label class="flex items-center gap-1">
                        <input type="checkbox" name="on_sale" value="1" @checked(request('on_sale')) class="rounded">
                        <span>Promoção</span>
                    </label>
                </div>

                {{-- Botões --}}
                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold px-4 py-1.5 rounded-md transition">
                        Filtrar
                    </button>
                    <a href="{{ route('cosmetics.index') }}"
                        class="text-gray-200 hover:text-white underline text-sm">
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
