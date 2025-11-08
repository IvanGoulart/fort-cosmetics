@extends('layouts.app')

@section('title', 'Histórico de Transações')

@section('content')
<div class="max-w-6xl mx-auto py-10 px-4">
    <h2 class="text-3xl font-bold mb-8 text-center text-gray-800 flex items-center justify-center gap-2">
        📜 Histórico de Compras e Devoluções
    </h2>

    {{-- ⚠️ Nenhuma transação --}}
    @if($transactions->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center text-gray-600">
            <p>Você ainda não realizou nenhuma transação.</p>
            <a href="{{ route('cosmetics.index') }}"
               class="inline-block mt-4 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md font-medium transition">
                Ir para a loja
            </a>
        </div>
    @else
        {{-- 📊 Tabela responsiva e estilizada --}}
        <div class="overflow-hidden rounded-xl shadow-lg border border-gray-200 bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-3 text-left">Data</th>
                        <th class="px-6 py-3 text-left">Item</th>
                        <th class="px-6 py-3 text-center">Tipo</th>
                        <th class="px-6 py-3 text-center">Valor</th>
                        <th class="px-6 py-3 text-center">Detalhes</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-sm">
                    @foreach($transactions as $t)
                        <tr class="hover:bg-gray-50 transition">
                            {{-- Data --}}
                            <td class="px-6 py-4 text-gray-700">
                                {{ \Carbon\Carbon::parse($t->executed_at)->format('d/m/Y H:i') }}
                            </td>

                            {{-- Nome + Imagem --}}
                            <td class="px-6 py-4 text-gray-800 font-medium flex items-center gap-3">
                                <img src="{{ $t->cosmetic->image ?? asset('images/default.png') }}"
                                     alt="{{ $t->cosmetic->name ?? 'Item removido' }}"
                                     class="w-10 h-10 object-contain bg-gray-100 rounded-md border">
                                <span>{{ $t->cosmetic->name ?? 'Item removido' }}</span>
                            </td>

                            {{-- Tipo da transação --}}
                            <td class="px-6 py-4 text-center">
                                @if($t->type === 'compra')
                                    <span class="inline-block bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">
                                        💰 Compra
                                    </span>
                                @else
                                    <span class="inline-block bg-red-100 text-red-700 text-xs font-semibold px-3 py-1 rounded-full">
                                        ↩️ Devolução
                                    </span>
                                @endif
                            </td>

                            {{-- Valor --}}
                            <td class="px-6 py-4 text-center text-gray-800 font-semibold">
                                {{ $t->amount }} <span class="text-indigo-600 font-bold">V-Bucks</span>
                            </td>

                            {{-- Ver detalhes --}}
                            <td class="px-6 py-4 text-center">
                                @if($t->cosmetic)
                                    <a href="{{ route('cosmetics.show', ['id' => $t->cosmetic->id, 'modo' => 'historico']) }}"
                                        class="inline-block text-indigo-600 hover:text-indigo-800 font-semibold underline transition">
                                        Ver detalhes
                                    </a>
                                @else
                                    <span class="text-gray-400 italic">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- 📄 Paginação --}}
        <div class="mt-6 flex justify-center">
            {{ $transactions->links() }}
        </div>
    @endif
</div>
@endsection
