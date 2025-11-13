<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FortCosmetics 🎮')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
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

        {{-- 🔹 Barra de filtros rápida (fixa abaixo da navbar)  --}}
        {{-- 🔹 Barra de filtros moderna --}}
        @if(request()->routeIs('cosmetics.index'))
        <div x-data="{ open: false }" class="bg-gray-100 border-b border-blue-200 shadow-sm text-gray-800">

            {{-- Cabeçalho do Filtro --}}
            <button 
                @click="open = !open"
                class="w-full flex items-center justify-between px-6 py-4 text-left font-semibold 
                    hover:bg-gray-200 transition text-gray-800">
                
                <span class="flex items-center gap-2">
                    🔍 <span>Filtros</span>
                </span>

                <span x-show="!open">▼</span>
                <span x-show="open">▲</span>
            </button>

            {{-- Conteúdo do filtro --}}
            <div x-show="open" x-collapse class="px-6 py-6 bg-gray-100 text-gray-800">

                <form method="GET" action="{{ route('cosmetics.index') }}"
                    class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-6 text-gray-800">

                    {{-- Campo de busca --}}
                    <div class="md:col-span-2">
                        <input type="text" name="name" value="{{ request('name') }}"
                            placeholder="Buscar por nome..."
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-800
                                placeholder-gray-500 shadow-sm focus:ring-2 focus:ring-blue-400 
                                focus:border-blue-400 transition">
                    </div>

                    {{-- Tipo e Raridade --}}
                    <div class="flex gap-3">
                        <select name="type" 
                            class="w-full rounded-lg border border-gray-300 py-2 px-3 text-sm text-gray-800 bg-white">
                            <option value="">Tipo</option>
                            <option value="outfit" @selected(request('type')==='outfit')>Outfit</option>
                            <option value="backpack" @selected(request('type')==='backpack')>Backpack</option>
                            <option value="pickaxe" @selected(request('type')==='pickaxe')>Pickaxe</option>
                        </select>

                        <select name="rarity"
                            class="w-full rounded-lg border border-gray-300 py-2 px-3 text-sm text-gray-800 bg-white">
                            <option value="">Raridade</option>
                            <option value="common" @selected(request('rarity')==='common')>Common</option>
                            <option value="uncommon" @selected(request('rarity')==='uncommon')>Uncommon</option>
                            <option value="rare" @selected(request('rarity')==='rare')>Rare</option>
                            <option value="epic" @selected(request('rarity')==='epic')>Epic</option>
                            <option value="legendary" @selected(request('rarity')==='legendary')>Legendary</option>
                        </select>
                    </div>

                    {{-- Checkboxes --}}
                    <div class="flex flex-wrap items-center gap-4 text-gray-800">
                        <label class="flex items-center gap-1 text-sm font-medium">
                            <input type="checkbox" name="is_new" value="1" @checked(request('is_new'))>
                            <span class="text-gray-800">Novo</span>
                        </label>
                        <label class="flex items-center gap-1 text-sm font-medium">
                            <input type="checkbox" name="is_shop" value="1" @checked(request('is_shop'))>
                            <span class="text-gray-800">Loja</span>
                        </label>
                        <label class="flex items-center gap-1 text-sm font-medium">
                            <input type="checkbox" name="on_sale" value="1" @checked(request('on_sale'))>
                            <span class="text-gray-800">Promoção</span>
                        </label>
                        <label class="flex items-center gap-1 text-sm font-medium">
                            <input type="checkbox" name="is_bundle" value="1" @checked(request('is_bundle'))>
                            <span class="text-gray-800">Bundles</span>
                        </label>
                    </div>

                    {{-- Data --}}
                    <div class="md:col-span-2 text-gray-800">
                        <label class="block text-sm font-medium text-gray-700 mb-1">📅 Data de Inclusão</label>

                        <div class="flex gap-3">
                            <input type="date" name="date_start" value="{{ request('date_start') }}"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 bg-white">

                            <span class="self-center text-gray-700 text-sm">até</span>

                            <input type="date" name="date_end" value="{{ request('date_end') }}"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-800 bg-white">
                        </div>
                    </div>

                    {{-- Botões --}}
                    <div class="flex items-center gap-4 md:justify-end">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow transition">
                            Filtrar
                        </button>

                        <a href="{{ route('cosmetics.index') }}"
                            class="text-blue-600 hover:text-blue-800 underline text-sm font-medium transition">
                            Limpar
                        </a>
                    </div>
                </form>
            </div>
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
