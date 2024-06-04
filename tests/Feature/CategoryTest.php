<?php

namespace Tests\Feature;

use App\Models\category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{

    use RefreshDatabase ,WithFaker ;


    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function testFetchAllCategories()
    {

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name'
                    ]
                ]
            ]);
    }

    /**
     * Test fetching a specific category.
     *
     * @return void
     */
    public function testFetchSpecificCategory()
    {
    
        $category = Category::factory()->create();

        $response = $this->getJson("/api/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson([
                "status"=> true,
                "message"=> "category exist",
                'category' => [
                    'id' => $category->id,
                    'category_name' => $category->name
                ]
            ]);
    }

    /**
     * Test adding a new category.
     *
     * @return void
     */
    public function testAddCategory()
    {
        

        $categoryData = [
            'name' => $this->faker->word
        ];

        $response = $this->postJson('/api/categories', $categoryData);

        $response->assertStatus(201)
            ->assertJson([
                "headers"=> [],
                "original"=> [
                   "status"=> true,
                   "message"=> "category added successfully !",
                   'category' => [
                    'category_name' => $categoryData['name']
                    ]
                ],"exception"=> null
            ]);

        $this->assertDatabaseHas('categories', $categoryData);
    }

    /**
     * Test updating a category.
     *
     * @return void
     */
    public function testUpdateCategory()
    {
        
        $category = Category::factory()->create();

        $updatedData = [
            'name' => $this->faker->word
        ];

        $response = $this->putJson("/api/categories/{$category->id}", $updatedData);

        $response->assertStatus(202)
            ->assertJson([
                "headers"=> [],
                "original"=> [
                   "status"=> true,
                   "message"=> "category updated successfully !",
                   'category' => [
                    'category_name' => $updatedData['name']
                    ]
                ],"exception"=> null
            ]);

        $this->assertDatabaseHas('categories', $updatedData);
    }

    /**
     * Test deleting a category.
     *
     * @return void
     */
    public function testDeleteCategory()
    {
        

        $category = Category::factory()->create();

        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson([
                "status"=> true,
                'message' => 'category deleted successfully !'
            ]);

    }
}
