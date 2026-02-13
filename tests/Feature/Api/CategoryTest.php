<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    /**
     * Get All Categories
     *
     * @return void
     */
    public function test_get_all_categories()
    {
        // Criando dados fake: gera 6 categorias no banco
        Category::factory()->count(6)->create();

        // Acessando a rota de categorias
        $response = $this->getJson('/categories');

        // Verificando se o status HTTP é 200 (OK)
        $response->assertOk();

        // (Debug temporário) Exibe a resposta no terminal enquanto você está desenvolvendo o teste
        // $response->dump();

        // Verificando se a resposta tem 6 categorias no nó "data"
        $response->assertJsonCount(6, 'data');
    }
}
