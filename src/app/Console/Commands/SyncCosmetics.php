<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Cosmetic;
use Exception;

class SyncCosmetics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:cosmetics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza cosméticos da API Fortnite com o banco de dados local.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Iniciando sincronização de cosméticos...');

        try {
            $response = Http::get('https://fortnite-api.com/v2/cosmetics/br');

            if ($response->failed()) {
                $this->error('❌ Falha ao acessar a API Fortnite.');
                return;
            }

            $items = $response->json()['data'] ?? [];
            $count = 0;

            foreach ($items as $item) {
                Cosmetic::updateOrCreate(
                    ['api_id' => $item['id']],
                    [
                        'name' => $item['name'] ?? 'Sem nome',
                        'type' => $item['type']['value'] ?? null,
                        'rarity' => $item['rarity']['value'] ?? null,
                        'image' => $item['images']['icon'] ?? null,
                        'price' => rand(100, 1500),
                        'is_new' => false,
                        'is_shop' => false,
                        'release_date' => isset($item['added'])
                            ? date('Y-m-d H:i:s', strtotime($item['added']))
                            : null,
                    ]
                );

                $count++;
            }

            $this->info("✅ Sincronização concluída: {$count} itens processados.");

        } catch (Exception $e) {
            $this->error('⚠️ Erro durante a sincronização: ' . $e->getMessage());
        }
    }
}
