<?php

namespace Tests\Feature;

use App\Models\category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api');

    }

    public function test_index()
    {
        Post::factory()->count(10)->create();

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'posts' => [
                    'data' => [
                        '*' => ['id', 'user_id', 'category_id', 'title', 'content']
                    ]
                ],
                'message'
            ]);
    }

    public function test_show()
    {
        $post = Post::factory()->create();

        $response = $this->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200);
    }


    public function test_store()
    {
        $category = category::factory()->create();
        $data = [
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'title' => 'New Post',
            'content' => 'Post content'
        ];

        Log::shouldReceive('info')->once();

        $response = $this->postJson('/api/posts', $data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('posts', $data);
    }



    public function test_update()
    {
        $post = Post::factory()->create();

        $data = [
            'title' => 'Updated Post',
            'content' => 'Updated content'
        ];

        Log::shouldReceive('info')->once();

        $response = $this->putJson("/api/posts/{$post->id}", $data);

        $response->assertStatus(200);
            

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Post',
            'content' => 'Updated content'
        ]);
    }




    public function test_delete()
    {
        $post = Post::factory()->create();

        Log::shouldReceive('info')->once();

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'post deleted successfully !'
            ]);

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id
        ]);
    }

}
