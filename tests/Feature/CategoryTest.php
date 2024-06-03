<?php

namespace Tests\Feature;

use App\Models\category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api');

    }

    public function test_create_category()
    {

        $response = $this->actingAs($this->user, 'api')->postJson('/api/Categories', [
            'name' => 'Sample Category',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('categories', [
            'name' => 'Sample Category',
        ]);

    }

    public function test_get_categories()
    {

        Category::factory()->count(3)->create();

        $response = $this->getJson('/api/Categories');

        $response->assertStatus(200);
        
    }

    public function test_get_category()
    {
        $category = Category::factory()->create();

        $response = $this->getJson("/api/Categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson([
                "status" => true,
                "message" => "category found",
                "category"=> ['id' => $category->id,'category_name' => $category->name],
                "codeStatus"=> 200
            
            ]);
    }

    public function test_update_category()
    {
        $category = Category::factory()->create();

        $response = $this->putJson("/api/Categories/{$category->id}", [
            'name' => 'Updated Category',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category',
        ]);
    }

    public function test_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("/api/Categories/{$category->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
