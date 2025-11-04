<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Cosmetic;

class SyncShopCosmetics extends Command
{
    protected $signature = 'sync:cosmetics-shop';
    protected $description = 'Atualiza os cosméticos atualmente disponíveis na loja (incluindo bundles e promoções).';

    public function handle()
    {
        $this->info('🛒 Atualizando cosméticos da loja...');

        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (compatible; FortSync/1.0)',
            'Accept' => 'application/json',
        ])->get('https://fortnite-api.com/v2/shop');

        if ($response->failed()) {
            $this->error('❌ Falha ao acessar a API da loja.');
            return;
        }

        $data = $response->json()['data'] ?? [];

        if (empty($data['entries'])) {
            $this->warn('⚠️ Nenhum item encontrado na loja.');
            return;
        }

        $entries = $data['entries'];

        // Zera flag "is_shop"
        Cosmetic::query()->update(['is_shop' => false]);

        $count = 0;

        foreach ($entries as $entry) {
            // --- 🎁 Trata BUNDLES ---
            if (isset($entry['bundle'])) {
                $bundle = Cosmetic::updateOrCreate(
                    ['api_id' => $entry['offerId']],
                    [
                        'name' => $entry['bundle']['name'] ?? 'Bundle sem nome',
                        'type' => 'bundle',
                        'price' => $entry['finalPrice'] ?? 0,
                        'regular_price' => $entry['regularPrice'] ?? $entry['finalPrice'] ?? 0,
                        'image' => $entry['bundle']['image']
                            ?? ($entry['newDisplayAsset']['renderImages'][0]['image'] ?? null),
                        'is_shop' => true,
                        'is_new' => false,
                        'release_date' => isset($entry['inDate'])
                            ? date('Y-m-d H:i:s', strtotime($entry['inDate']))
                            : now(),
                    ]
                );

                $count++;

                // 🧩 Adiciona itens dentro do bundle (cars, tracks, etc.)
                $bundleItems = $entry['cars'] ?? $entry['tracks'] ?? $entry['instruments'] ?? [];

                foreach ($bundleItems as $item) {
                    Cosmetic::updateOrCreate(
                        ['api_id' => $item['id']],
                        [
                            'name' => $item['name'] ?? 'Sem nome',
                            'type' => $item['type']['value'] ?? 'cosmetic',
                            'rarity' => $item['rarity']['value'] ?? null,
                            'bundle_id' => $bundle->id,
                            'price' => 0, // item faz parte do bundle, sem custo direto
                            'image' => $item['images']['small']
                                ?? $item['images']['large']
                                ?? null,
                            'is_shop' => true,
                            'is_new' => false,
                            'release_date' => isset($item['added'])
                                ? date('Y-m-d H:i:s', strtotime($item['added']))
                                : now(),
                        ]
                    );

                    $count++;
                }

                continue;
            }

            // --- 🎨 Itens individuais (fora de bundles) ---
            $cosmetics = $entry['brItems'] ?? $entry['cars'] ?? $entry['tracks'] ?? $entry['instruments'] ?? null;

            if (!$cosmetics) {
                $this->warn('⚠️ Entrada sem cosméticos reconhecíveis ignorada.');
                continue;
            }

            foreach ($cosmetics as $item) {
                Cosmetic::updateOrCreate(
                    ['api_id' => $item['id']],
                    [
                        'name' => $item['name'] ?? 'Sem nome',
                        'type' => $item['type']['value'] ?? 'cosmetic',
                        'rarity' => $item['rarity']['value'] ?? null,
                        'price' => $entry['finalPrice'] ?? 0,
                        'regular_price' => $entry['regularPrice'] ?? $entry['finalPrice'] ?? 0,
                        'image' => $item['images']['icon']
                            ?? $item['images']['small']
                            ?? $item['images']['large']
                            ?? ($entry['newDisplayAsset']['renderImages'][0]['image'] ?? null),
                        'is_shop' => true,
                        'is_new' => false,
                        'release_date' => isset($entry['inDate'])
                            ? date('Y-m-d H:i:s', strtotime($entry['inDate']))
                            : now(),
                    ]
                );

                $count++;
            }
        }

        $this->info("✅ {$count} cosméticos sincronizados com sucesso (incluindo bundles)!");
    }
}
