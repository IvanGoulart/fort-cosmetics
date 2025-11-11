<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Cosmetic;

class SyncNewCosmetics extends Command
{
    protected $signature = 'sync:cosmetics-new';
    protected $description = 'Sincroniza novos cosméticos adicionados recentemente.';

    public function handle()
    {
        $this->info('* Sincronizando cosméticos novos...');

        // Endpoint correto e headers para evitar bloqueio
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (compatible; FortSync/1.0)',
            'Accept' => 'application/json',
        ])->get('https://fortnite-api.com/v2/cosmetics/new');

        if ($response->failed()) {
            $this->error('❌ Falha ao acessar a API de cosméticos novos.');
            return;
        }

        $data = $response->json()['data'] ?? [];
        $items = $data['items']['br'] ?? [];

        if (empty($items)) {
            $this->warn('⚠️ Nenhum novo cosmético encontrado.');
            return;
        }

        $count = 0;
        foreach ($items as $item) {
            if (empty($item['id'])) {
                $this->warn('⚠️ Item sem ID ignorado.');
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
                    'is_new' => true,
                    'is_shop' => false,
                    'release_date' => isset($item['added'])
                        ? date('Y-m-d H:i:s', strtotime($item['added']))
                        : now(),
                ]
            );

            $count++;
        }

        $this->info("✅ {$count} novos cosméticos sincronizados!");
    }
}
