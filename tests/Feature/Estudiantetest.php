<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Paralelo;
use Illuminate\Support\Facades\Log;

class EstudianteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $paralelo = Paralelo::factory()->create();

        $estudiante = [
            'name' => 'Juan Pérez',
            'ci' => '1234567890',
            'correo' => 'juan@example.com',
            'paralelos_id' => $paralelo->id,
        ];

        Log::info('Enviando datos al endpoint /api/student/store', $estudiante);

        $response = $this->postJson('/api/student/store', $estudiante);

        Log::info('Respuesta recibida:', $response->json());

        $this->assertDatabaseHas('estudiantes', $estudiante);
    }
}
