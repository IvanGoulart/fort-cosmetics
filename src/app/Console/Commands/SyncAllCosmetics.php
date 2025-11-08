<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CosmeticSyncService;

class SyncAllCosmetics extends Command
{
    protected $signature = 'sync:cosmetics-all';
    protected $description = 'Sincroniza todos os cosméticos (carga inicial).';

    protected CosmeticSyncService $syncService;

    public function __construct(CosmeticSyncService $syncService)
    {
        parent::__construct();
        $this->syncService = $syncService;
    }

    public function handle()
    {
        $this->info('📦 Iniciando sincronização de cosméticos...');

        try {
            $count = $this->syncService->syncAll();
            if ($count === 0) {
                $this->warn('⚠️ Nenhum cosmético encontrado.');
            } else {
                $this->info("✅ {$count} cosméticos sincronizados com sucesso!");
            }
        } catch (\Exception $e) {
            $this->error('❌ Erro: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
