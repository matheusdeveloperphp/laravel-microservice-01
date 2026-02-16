<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $endpoint = '/companies';

    /**
     * Get All Companies
     *
     * @return void
     */
    public function test_get_all_companies()
    {
        Company::factory()->count(6)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(200);
        $response->assertJsonCount(6, 'data');
    }

    /**
     * Error Get Single Company
     *
     * @return void
     */
    public function test_error_get_single_company()
    {
        $company = 'fake-uuid';

        $response = $this->getJson("{$this->endpoint}/{$company}");

        $response->assertStatus(404);
    }

    /**
     * Get Single Company
     *
     * @return void
     */
    public function test_get_single_company()
    {
        $company = Company::factory()->create();

        $response = $this->getJson("{$this->endpoint}/{$company->uuid}");

        $response->assertStatus(200);
    }

    /**
     * Validation Store Company
     *
     * @return void
     */
    public function test_validations_store_company()
    {
        $response = $this->postJson($this->endpoint, [
            'name' => '',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Store Company
     *
     * @return void
     */
    public function test_store_company()
    {
        $category = Category::factory()->create();

        $image = UploadedFile::fake()->image('eti.png');

        $response = $this->post($this->endpoint, [
            'category_id' => $category->id,
            'name' => 'EspecializaTi',
            'email' => 'contato@especializati.com.br',
            'whatsapp' => '64981701406',
            'image' => $image,
        ], [
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(201);
    }

    /**
     * Update Company
     *
     * @return void
     */
    public function test_update_company()
    {
        $company = Company::factory()->create();
        $category = Category::factory()->create();

        $data = [
            'category_id' => $category->id,
            'name' => 'EspecializaTi',
            'email' => 'contato@especializati.com.br',
            'whatsapp' => '64981701406',
        ];

        // Test fake company
        $response = $this->putJson("$this->endpoint/fake-company", $data);
        $response->assertStatus(404);

        // Test validation error
        $response = $this->putJson("$this->endpoint/{$company->uuid}", []);
        $response->assertStatus(422);

        // Test success update
        $response = $this->putJson("$this->endpoint/{$company->uuid}", $data);
        $response->assertStatus(200);
    }

    /**
     * Delete Company
     *
     * @return void
     */
    public function test_delete_company()
    {
        $company = Company::factory()->create();

        // Fake delete
        $response = $this->deleteJson("{$this->endpoint}/fake-company");
        $response->assertStatus(404);

        // Success delete
        $response = $this->deleteJson("{$this->endpoint}/{$company->uuid}");
        $response->assertStatus(204);
    }
}
