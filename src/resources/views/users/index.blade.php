@extends('layouts.app')

@section('title', 'Usuários - FortCosmetics')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <h2 class="text-3xl font-bold text-center mb-8">🌍 Comunidade FortCosmetics</h2>

    {{-- Mensagens de sessão (caso precise no futuro) --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 text-sm p-2 rounded mb-4 text-center">
            {{ session('success') }}
        </div>
    @endif

    {{-- Grade de usuários --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($users as $user)
            <div class="bg-white shadow rounded-lg p-6 text-center hover:shadow-lg transition">
                {{-- Avatar inicial (ou futuro upload) --}}
                <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white text-2xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <h3 class="font-semibold text-gray-800 text-lg">{{ $user->name }}</h3>
                <p class="text-sm text-gray-500 mb-2">{{ $user->email }}</p>

                {{-- Exibir V-Bucks, se aplicável --}}
                @if(isset($user->vbucks))
                    <p class="text-indigo-600 font-bold text-sm">💰 {{ $user->vbucks }} V-Bucks</p>
                @endif

                {{-- Detalhes / futuro perfil --}}
                <a href="{{ route('users.show', $user->id) }}"
                class="inline-block mt-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-3 py-1 rounded-md transition">
                    Ver perfil
                </a>               
            </div>
        @empty
            <p class="col-span-full text-center text-gray-500 py-10">
                Nenhum usuário encontrado.
            </p>
        @endforelse
    </div>

    {{-- Paginação --}}
    <div class="mt-8">
        {{ $users->links() }}
    </div>
</div>
@endsection
