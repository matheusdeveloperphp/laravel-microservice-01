<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    protected $endpoint = '/categories';

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
        $response = $this->getJson("{$this->endpoint}");

        // Verificando se o status HTTP é 200 (OK)
        $response->assertOk();

        // (Debug temporário) Exibe a resposta no terminal enquanto você está desenvolvendo o teste
        // $response->dump();

        // Verificando se a resposta tem 6 categorias no nó "data"
        $response->assertJsonCount(6, 'data');
    }


    /**
     * Error gets Single Category
     * @return void
     */
    public function test_error_get_single_category()
    {
        $category = 'fake-url';

        // Acessando a rota de categorias
        $response = $this->getJson("{$this->endpoint}/{$category}");
        $response->assertStatus(404);
    }

    /**
     *  Get Single Category
     * @return void
     */
    public function test_get_single_category()
    {
        // Criando uma categoria fake
        $category = Category::factory()->create();
        $response = $this->getJson("{$this->endpoint}/{$category->url}");

        //Debug temporário para exibir a resposta no terminal enquanto você está desenvolvendo o teste
        //$response->dump();

        $response->assertStatus(200);
    }


    /**
     *  Validation Store Category
     * @return void
     */
    public function test_validations_store_category()
    {
        // Criando uma categoria fake
        $category = Category::factory()->create();


        //Inserindo dados inválidos (nome vazio)
        $response = $this->postJson($this->endpoint, [
            'title' => '',
            'description' => '',
        ]);


        //Debug temporário para exibir a resposta no terminal enquanto você está desenvolvendo o teste
        //$response->dump();

        // Verificando se o status HTTP é 422 (Unprocessable Entity) e se os erros de validação estão presentes para os campos "title" e "description"
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'description']);
    }


    /**
     *  Store Category
     * @return void
     */
    public function test_store_category()
    {
        // Criando uma categoria fake
        $category = Category::factory(1)->create();


        //Inserindo dados inválidos (nome vazio)
        $response = $this->postJson($this->endpoint, [
            'title' => 'Category 01',
            'description' => 'Description of category 01',
        ]);


        //Debug temporário para exibir a resposta no terminal enquanto você está desenvolvendo o teste
        $response->dump();


        $response->assertStatus(201);
    }



}
