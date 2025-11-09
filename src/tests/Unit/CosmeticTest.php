<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Cosmetic;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CosmeticTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cosmetic_pode_ter_itens_e_bundle_pai()
    {
        $bundle = Cosmetic::factory()->create(['type' => 'bundle']);
        $item = Cosmetic::factory()->create(['bundle_id' => $bundle->id]);

        $this->assertTrue($bundle->items->contains($item));
        $this->assertEquals($bundle->id, $item->bundle->id);
    }
}
