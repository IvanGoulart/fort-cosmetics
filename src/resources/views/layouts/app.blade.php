<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FortCosmetics 🎮')</title>

    {{-- Importa o CSS e JS gerados pelo Vite (Tailwind + scripts) --}}
   @vite('resources/css/app.css')

</head>
<body class="bg-gray-50 text-gray-900 flex flex-col min-h-screen">

    {{-- Cabeçalho global (opcional — pode ser sobrescrito em views) --}}
    <nav class="bg-blue-700 text-white shadow-md fixed top-0 left-0 w-full z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
            <a href="{{ route('cosmetics.index') }}" class="text-2xl font-bold tracking-wide">FortCosmetics 🎮</a>
            <ul class="hidden md:flex gap-6">
                <li><a href="{{ route('cosmetics.index') }}" class="hover:text-yellow-300 transition">Início</a></li>
                <li><a href="#" class="hover:text-yellow-300 transition">Sobre</a></li>
                <li><a href="#" class="hover:text-yellow-300 transition">Contato</a></li>

               
                <li>@auth
                    <div class="fixed top-4 right-6 bg-indigo-600 text-white px-4 py-2 rounded-lg shadow-md">
                        💰 {{ auth()->user()->vbucks }} V-Bucks
                    </div>
                    @endauth
                </li>
                <li>
                    @auth
                    <form method="POST" action="{{ route('logout') }}" >
                    @csrf
                    <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded transition">
                        Sair
                    </button>
                </form>
                    @endauth
                </li>
            </ul>
        </div>
    </nav>

    {{-- Conteúdo principal --}}
    <main class="flex-1 pt-24">
        @yield('content')
    </main>

    {{-- Rodapé padrão --}}
    <footer class="bg-blue-700 text-white py-6 text-center">
       
        <p class="text-sm">&copy; {{ date('Y') }} FortCosmetics - Todos os direitos reservados.</p>
        <p class="text-xs text-blue-200 mt-1">Desenvolvido por Ivan Goulart 💻</p>
    </footer>

</body>
</html>
