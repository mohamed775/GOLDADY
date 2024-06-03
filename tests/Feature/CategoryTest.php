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
    }

    public function test_create_category()
    {
        $response = $this->actingAs($this->user, 'api')->postJson('/api/categories', [
            'name' => 'Sample Category',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('categories', [
            'name' => 'Sample Category',
        ]);
    }

    public function test_get_categories()
    {
        $this->actingAs($this->user, 'api');

        Category::factory()->count(3)->create();

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'name', 'created_at', 'updated_at']
            ]);
    }

    public function test_get_category()
    {
        $this->actingAs($this->user, 'api');
        $category = Category::factory()->create();

        $response = $this->getJson("/api/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $category->id,
                'name' => $category->name,
                'created_at' => $category->created_at->toISOString(),
                'updated_at' => $category->updated_at->toISOString(),
            ]);
    }

    public function test_update_category()
    {
        $this->actingAs($this->user, 'api');
        $category = Category::factory()->create();

        $response = $this->putJson("/api/categories/{$category->id}", [
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
        $this->actingAs($this->user, 'api');
        $category = Category::factory()->create();

        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
