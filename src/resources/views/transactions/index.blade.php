@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10 px-4">
    <h2 class="text-2xl font-bold mb-6 text-center">📜 Histórico de Compras e Devoluções</h2>

    @if($transactions->isEmpty())
        <p class="text-center text-gray-600">Você ainda não realizou nenhuma transação.</p>
    @else
        <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Valor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($transactions as $t)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($t->executed_at)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800 font-medium">
                                {{ $t->cosmetic->name ?? 'Item removido' }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold">
                                @if($t->type === 'compra')
                                    <span class="text-green-600">Compra</span>
                                @else
                                    <span class="text-red-600">Devolução</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $t->amount }} V-Bucks
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($t->cosmetic)
                                    <a href="{{ route('cosmetics.show', $t->cosmetic->id) }}"
                                       class="text-indigo-600 hover:text-indigo-800 font-semibold">
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

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    @endif
</div>
@endsection
