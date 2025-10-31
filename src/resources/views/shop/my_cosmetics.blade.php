@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10 px-4">
    <h2 class="text-2xl font-bold mb-6 text-center">Meus Cosméticos</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 text-sm p-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-800 text-sm p-2 rounded mb-4">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($purchases as $item)
            <div class="bg-white shadow rounded-lg p-4 text-center">
                <img src="{{ $item->image }}" alt="{{ $item->name }}" class="w-40 h-40 mx-auto object-contain mb-3">
                <h4 class="font-semibold">{{ $item->name }}</h4>
                <p class="text-sm text-gray-500 mb-2">{{ $item->rarity }}</p>

                @if(!$item->pivot->returned)
                    <form method="POST" action="{{ route('refund', $item->id) }}">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-sm py-1 px-3 rounded">
                            Devolver
                        </button>
                    </form>
                @else
                    <span class="text-xs text-gray-500 italic">Devolvido</span>
                @endif
            </div>
        @empty
            <p class="text-center col-span-4 text-gray-600">Você ainda não comprou nenhum cosmético.</p>
        @endforelse
    </div>
</div>
@endsection
