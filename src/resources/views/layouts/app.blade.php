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
                <li><a href="#" class="hover:text-yellow-300 transition">Sobre</a></li>
                @auth
                    <li><a href="{{ route('my.cosmetics') }}" class="mr-4 hover:underline">🎒 Meus Cosméticos</a></li>
                    <li><a href="{{ route('transactions.index') }}" class="hover:underline mr-4">📜 Histórico</a></li>
                @endauth
            </ul>

            {{-- Ações de usuário --}}
            <div class="flex items-center gap-4">
                @auth
                    {{-- Mostra saldo e nome --}}
                    <div class="flex items-center gap-2 bg-blue-600 px-3 py-2 rounded-lg shadow-md">
                        <span class="font-medium">💰 {{ auth()->user()->vbucks ?? 0 }} V-Bucks</span>
                    </div>

                    <span class="hidden sm:inline font-semibold">Olá, {{ Auth::user()->name ?? 'Usuário' }}</span>

                    {{-- Botão de logout --}}
                    <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Deseja realmente sair?')" class="inline">
                        @csrf
                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                            Sair
                        </button>
                    </form>
                @else
                    {{-- Link de login --}}
                    <a href="{{ route('login') }}"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Entrar
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- 🔹 Conteúdo principal --}}
    <main class="flex-1 pt-[90px] pb-10">
        @yield('content')
    </main>

    {{-- 🔹 Rodapé --}}
    <footer class="bg-blue-700 text-white py-6 text-center mt-auto">
        <p class="text-sm">&copy; {{ date('Y') }} FortCosmetics - Todos os direitos reservados.</p>
        <p class="text-xs text-blue-200 mt-1">Desenvolvido por Ivan Goulart 💻</p>
    </footer>

</body>
</html>
