<?php

namespace Tests\Feature;

use App\Models\category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_create_post()
    {
        $category = category::factory()->create();

        $response = $this->actingAs($this->user, 'api')->postJson('/api/posts', [
            'title' => 'Sample Post',
            'body' => 'This is a sample post.',
            'category_id' => $category->id,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('posts', [
            'title' => 'Sample Post',
        ]);
    }

    public function test_get_posts()
    {
        $this->actingAs($this->user, 'api');

        Post::factory()->count(3)->create();

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'title', 'body', 'user_id', 'category_id', 'created_at', 'updated_at']
            ]);
    }

    public function test_get_post()
    {
        $this->actingAs($this->user, 'api');
        $post = Post::factory()->create();

        $response = $this->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $post->id,
                'title' => $post->title,
                'body' => $post->body,
                'user_id' => $post->user_id,
                'category_id' => $post->category_id,
                'created_at' => $post->created_at->toISOString(),
                'updated_at' => $post->updated_at->toISOString(),
            ]);
    }

    public function test_update_post()
    {
        $this->actingAs($this->user, 'api');
        $post = Post::factory()->create();

        $response = $this->putJson("/api/posts/{$post->id}", [
            'title' => 'Updated Post',
            'body' => 'This is an updated post.',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Post',
        ]);
    }

    public function test_delete_post()
    {
        $this->actingAs($this->user, 'api');
        $post = Post::factory()->create();

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }
}
