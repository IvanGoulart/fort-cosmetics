@extends('layouts.app')

@section('title', $user->name . ' - Perfil')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    {{-- Cabeçalho do perfil --}}
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8 text-center">
        <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white text-3xl font-bold shadow-md">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>

        <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
        <p class="text-gray-500 text-sm mb-2">{{ $user->email }}</p>

        <p class="text-indigo-600 font-semibold">💰 {{ $user->vbucks ?? 0 }} V-Bucks</p>

        {{-- Botão voltar --}}
        <div class="mt-4">
            <a href="{{ route('users.index') }}" 
               class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-semibold px-4 py-2 rounded-md transition">
                ← Voltar à comunidade
            </a>
        </div>
    </div>

    {{-- Cosméticos possuídos --}}
    <div class="bg-white shadow-lg rounded-lg p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">
            🎒 Cosméticos de {{ $user->name }}
        </h3>

        @if($user->cosmetics->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($user->cosmetics as $item)
                    @include('partials.cosmetic-card', ['item' => $item, 'ownedCosmetics' => [$item->id]])
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-500 py-6">
                Nenhum cosmético encontrado para este usuário.
            </p>
        @endif
    </div>
</div>
@endsection
