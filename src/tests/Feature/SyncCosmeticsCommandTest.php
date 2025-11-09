<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Cosmetic;

class SyncCosmeticsCommandTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_comando_sincroniza_cosmeticos_da_api(): void
    {
        Http::fake([
            'https://fortnite-api.com/v2/cosmetics' => Http::response([
                'data' => [
                    'br' => [
                        [
                            'id' => 'CID_001',
                            'name' => 'Teste Skin',
                            'type' => ['value' => 'outfit'],
                            'rarity' => ['value' => 'rare'],
                            'images' => ['icon' => 'https://example.com/icon.png'],
                            'added' => now()->toISOString(),
                        ],
                    ],
                ],
            ], 200),
        ]);

        $this->artisan('sync:cosmetics-all')
            ->expectsOutputToContain('cosméticos sincronizados com sucesso') // 🔹 só checa a parte final
            ->assertExitCode(0);

        $this->assertDatabaseHas('cosmetics', [
            'api_id' => 'CID_001',
            'name' => 'Teste Skin',
        ]);
    }
}
