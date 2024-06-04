<?php

namespace Tests\Feature;

use App\Models\category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase , WithFaker;

    protected $user ;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);

    }
    /**
     * Test fetching all posts.
     *
     * @return void
     */
    public function testFetchAllPosts()
    {
    
        $posts = Post::factory()->count(5)->create();

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'post_title',
                        'post_content',
                        'created_By' => [
                            'id',
                            'userName',
                            'user_email',
                            
                        ],
                        'post_category' => [
                            'id',
                            'category_name',
                           
                        ]
                    ]
                ]
            ]);
    }

    /**
     * Test fetching a specific post.
     *
     * @return void
     */
    public function testFetchSpecificPost()
    {
    
        $post = Post::factory()->create();

        $response = $this->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200);
    }

    /**
     * Test adding a new post.
     *
     * @return void
     */
    public function testAddPost()
    {
        

        $category = Category::factory()->create();

        $postData = [
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph
        ];

        $response = $this->postJson('/api/posts', $postData);

        $response->assertStatus(201);       

    }

    /**
     * Test updating a post.
     *
     * @return void
     */
    public function testUpdatePost()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::factory()->create();

        $updatedData = [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph
        ];

        $response = $this->putJson("/api/posts/{$post->id}", $updatedData);

        $response->assertStatus(202);

    }

    /**
     * Test deleting a post.
     *
     * @return void
     */
    public function testDeletePost()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $post = Post::factory()->create();

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJson([
                "status"=> true,
                'message' => 'post deleted successfully !'
            ]);

    }
    

}
