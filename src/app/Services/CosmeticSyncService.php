<?php

namespace App\Services;

use App\Models\Cosmetic;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CosmeticSyncService
{
    protected string $baseUrl = 'https://fortnite-api.com/v2/cosmetics';

    protected string $shopUrl = 'https://fortnite-api.com/v2/shop';

    /**
     * Extract the best available image from item data
     *
     * @param  array  $item  Item data from API
     * @param  array|null  $entry  Optional entry data for fallback images
     * @return string|null Image URL or null
     */
    protected function extractImage(array $item, ?array $entry = null): ?string
    {
        $image = $item['images']['icon']
            ?? $item['images']['smallIcon']
            ?? $item['images']['small']
            ?? $item['images']['large']
            ?? $item['images']['featured']
            ?? null;

        // Fallback to entry display asset if available
        if ($image === null && $entry !== null) {
            $image = $entry['newDisplayAsset']['renderImages'][0]['image'] ?? null;
        }

        return $image;
    }

    /**
     * Sincroniza todos os cosméticos (carga completa)
     */
    public function syncAll(): int
    {
        return $this->fetchAndSync("{$this->baseUrl}");
    }

    /**
     * Sincroniza apenas os cosméticos novos
     */
    public function syncNew(): int
    {
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (compatible; FortSync/1.0)',
            'Accept' => 'application/json',
        ])->get("{$this->baseUrl}/new");

        if ($response->failed()) {
            throw new \RuntimeException('Falha ao acessar a API de cosméticos novos.');
        }

        $data = $response->json()['data']['items']['br'] ?? [];

        if (empty($data)) {
            return 0;
        }

        $count = 0;
        foreach ($data as $item) {
            if (empty($item['id'])) {
                continue;
            }

            Cosmetic::updateOrCreate(
                ['api_id' => $item['id']],
                [
                    'name' => $item['name'] ?? 'Sem nome',
                    'type' => $item['type']['value'] ?? null,
                    'rarity' => $item['rarity']['value'] ?? null,
                    'image' => $this->extractImage($item),
                    'price' => 0, // Price will be updated when item appears in shop
                    'is_new' => true,
                    'is_shop' => false,
                    'release_date' => isset($item['added'])
                        ? date('Y-m-d H:i:s', strtotime($item['added']))
                        : now(),
                ]
            );

            $count++;
        }

        return $count;
    }

    /**
     * Método utilitário interno para sincronização geral
     */
    protected function fetchAndSync(string $url): int
    {
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (compatible; FortSync/1.0)',
            'Accept' => 'application/json',
        ])->get($url);

        if ($response->failed()) {
            throw new \RuntimeException('Falha ao acessar a API de cosméticos.');
        }

        $data = $response->json()['data']['br'] ?? [];

        $count = 0;
        foreach ($data as $item) {
            if (empty($item['id'])) {
                continue;
            }

            Cosmetic::updateOrCreate(
                ['api_id' => $item['id']],
                [
                    'name' => $item['name'] ?? 'Sem nome',
                    'type' => $item['type']['value'] ?? null,
                    'rarity' => $item['rarity']['value'] ?? null,
                    'image' => $this->extractImage($item),
                    'price' => 0, // Price will be updated when item appears in shop
                    'is_new' => false,
                    'is_shop' => false,
                    'release_date' => isset($item['added'])
                        ? date('Y-m-d H:i:s', strtotime($item['added']))
                        : null,
                ]
            );
            $count++;
        }
        Log::info("[Sync] Total de {$count} cosméticos sincronizados.");

        return $count;
    }

    public function syncShop(): int
    {
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (compatible; FortSync/1.0)',
            'Accept' => 'application/json',
        ])->get($this->shopUrl);

        if ($response->failed()) {
            throw new \RuntimeException('Falha ao acessar a API da loja.');
        }

        $data = $response->json()['data'] ?? [];

        if (empty($data['entries'])) {
            return 0;
        }

        $entries = $data['entries'];

        // Zera flag "is_shop" antes de atualizar
        Cosmetic::query()->update(['is_shop' => false]);

        $count = 0;

        foreach ($entries as $entry) {
            // --- 🎁 Bundles ---
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

                // Itens dentro do bundle
                $bundleItems = $entry['brItems']
                    ?? $entry['cars']
                    ?? $entry['tracks']
                    ?? $entry['instruments']
                    ?? [];

                foreach ($bundleItems as $item) {
                    if (empty($item['id'])) {
                        continue;
                    }

                    Cosmetic::updateOrCreate(
                        ['api_id' => $item['id']],
                        [
                            'name' => $item['name'] ?? 'Sem nome',
                            'type' => $item['type']['value'] ?? 'cosmetic',
                            'rarity' => $item['rarity']['value'] ?? null,
                            'bundle_id' => $bundle->id,
                            'price' => 0,
                            'regular_price' => 0,
                            'image' => $this->extractImage($item, $entry),
                            'is_shop' => true,
                            'is_new' => false,
                            'release_date' => isset($item['added'])
                                ? date('Y-m-d H:i:s', strtotime($item['added']))
                                : now(),
                        ]
                    );

                    $count++;
                }

                continue; // pula para próxima entrada
            }

            // --- 🎨 Itens individuais (fora de bundles) ---
            $cosmetics = $entry['brItems']
                ?? $entry['cars']
                ?? $entry['tracks']
                ?? $entry['instruments']
                ?? null;

            if (! $cosmetics) {
                Log::warning('Entrada sem cosméticos reconhecíveis.', ['entry' => $entry['offerId'] ?? 'unknown']);

                continue;
            }

            foreach ($cosmetics as $item) {
                if (empty($item['id'])) {
                    continue;
                }

                Cosmetic::updateOrCreate(
                    ['api_id' => $item['id']],
                    [
                        'name' => $item['name'] ?? 'Sem nome',
                        'type' => $item['type']['value'] ?? 'cosmetic',
                        'rarity' => $item['rarity']['value'] ?? null,
                        'price' => $entry['finalPrice'] ?? 0,
                        'regular_price' => $entry['regularPrice'] ?? $entry['finalPrice'] ?? 0,
                        'image' => $this->extractImage($item, $entry),
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

        return $count;
    }
}
