<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Cosmetic;

class SyncAllCosmetics extends Command
{
    protected $signature = 'sync:cosmetics-all';
    protected $description = 'Sincroniza todos os cosméticos (carga inicial).';

    public function handle()
    {
        $this->info('📦 Baixando lista completa de cosméticos...');

        // Novo endpoint correto
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (compatible; FortSync/1.0)',
            'Accept' => 'application/json',
        ])->get('https://fortnite-api.com/v2/cosmetics');

        if ($response->failed()) {
            $this->error('❌ Falha ao acessar a API de cosméticos.');
            return;
        }

        $data = $response->json()['data'] ?? [];
        $all = $data['br'] ?? [];

        if (empty($all)) {
            $this->warn('⚠️ Nenhum cosmético encontrado na API.');
            return;
        }

        $count = 0;

        foreach ($all as $item) {
            // Garantia contra entradas malformadas
            if (empty($item['id'])) {
                $this->warn("⚠️ Item sem ID ignorado.");
                continue;
            }

            $image = $item['images']['icon']
                ?? $item['images']['smallIcon']
                ?? $item['images']['featured']
                ?? null;

            Cosmetic::updateOrCreate(
                ['api_id' => $item['id']],
                [
                    'name' => $item['name'] ?? 'Sem nome',
                    'type' => $item['type']['value'] ?? null,
                    'rarity' => $item['rarity']['value'] ?? null,
                    'image' => $image,
                    'price' => rand(100, 1500),
                    'is_new' => false,
                    'is_shop' => false,
                    'release_date' => isset($item['added'])
                        ? date('Y-m-d H:i:s', strtotime($item['added']))
                        : null,
                ]
            );

            $count++;

            // Atualiza progresso a cada 200 itens
            if ($count % 200 === 0) {
                $this->info("⏳ Processados {$count} cosméticos...");
            }
        }

        $this->info("✅ {$count} cosméticos sincronizados com sucesso!");
    }
}
