<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    
        $this->withoutMiddleware();
    }
    

    public function test_create_category()
    {
        $response = $this->postJson('/api/categories/create', [
            'name' => 'Electronics',
            'description' => 'All kinds of electronics',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Category created successfully',
                 ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Electronics',
            'description' => 'All kinds of electronics',
        ]);
    }

    public function test_get_all_categories()
    {
        Category::create([
            'name' => 'Electronics',
            'description' => 'All kinds of electronics',
        ]);

        Category::create([
            'name' => 'Books',
            'description' => 'All kinds of books',
        ]);

        $response = $this->getJson('/api/categories/index');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'All categories',
                 ])
                 ->assertJsonStructure([
                    'categories' => [
                        [
                            'id',
                            'name',
                            'description',
                            'created_at',
                            'updated_at',
                        ]
                    ],
                 ]);
    }

    public function test_update_category()
    {
        $category = Category::create([
            'name' => 'Electronics',
            'description' => 'All kinds of electronics',
        ]);

        $response = $this->putJson("/api/categories/update/{$category->id}", [
            'name' => 'Updated Electronics',
            'description' => 'Updated description',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Category updated successfully',
                 ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Electronics',
            'description' => 'Updated description',
        ]);
    }

    public function test_delete_category()
    {
        $category = Category::create([
            'name' => 'Electronics',
            'description' => 'All kinds of electronics',
        ]);

        $response = $this->deleteJson("/api/categories/delete/{$category->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Category deleted successfully',
                 ]);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
