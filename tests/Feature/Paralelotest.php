<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Paralelo;
use App\Models\Estudiante;
use Illuminate\Support\Facades\Log;

class ParaleloTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $p1 = Paralelo::factory()->create(['nombre' => 'P1']);
        $p2 = Paralelo::factory()->create(['nombre' => 'P2']);

        Log::info('Paralelos creados:', [
            'P1' => $p1->toArray(),
            'P2' => $p2->toArray(),
        ]);

        $response = $this->getJson('/api/paralelo/index');

        Log::info('Respuesta del endpoint /api/paralelo/index:', $response->json());

        $response->assertStatus(200)
            ->assertJsonFragment(['nombre' => 'P1'])
            ->assertJsonFragment(['nombre' => 'P2'])
            ->assertJsonStructure([
                '*' => ['id', 'nombre']
            ]);
    }
}
