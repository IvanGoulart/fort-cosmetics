<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Cosmetic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ShopControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    /** @test */
    public function test_usuario_pode_comprar_um_item_individual()
    {
        $user = User::factory()->create(['vbucks' => 1000]);
        $cosmetic = Cosmetic::factory()->create([
            'price' => 500,
            'type' => 'outfit', // ✅ força ser item individual
        ]);

        $this->actingAs($user)
            ->post(route('buy', $cosmetic->id))
            ->assertRedirect()
            ->assertSessionHas('success', 'Item comprado com sucesso!');

        $this->assertDatabaseHas('user_cosmetics', [
            'user_id' => $user->id,
            'cosmetic_id' => $cosmetic->id,
            'returned' => false,
        ]);
    }

    /** @test */
    public function usuario_nao_pode_comprar_sem_saldo()
    {
        $user = User::factory()->create(['vbucks' => 100]);
        $cosmetic = Cosmetic::factory()->create(['price' => 500]);

        $this->actingAs($user)
            ->post(route('buy', $cosmetic->id))
            ->assertSessionHas('error', 'Créditos insuficientes.');
    }

    /** @test */
    public function usuario_pode_devolver_item_e_receber_reembolso()
    {
        $user = User::factory()->create(['vbucks' => 100]);
        $cosmetic = Cosmetic::factory()->create([
            'price' => 200,
            'type' => 'outfit', // ✅ garante que não será tratado como bundle
        ]);
        $user->cosmetics()->attach($cosmetic->id, ['returned' => false]);

        $this->actingAs($user)
            ->post(route('refund', $cosmetic->id))
            ->assertSessionHas('success', 'Item devolvido e créditos reembolsados!');

        $this->assertEquals(300, $user->fresh()->vbucks);
        $this->assertDatabaseHas('user_cosmetics', [
            'user_id' => $user->id,
            'cosmetic_id' => $cosmetic->id,
            'returned' => true,
        ]);
    }
}
