<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    
        $this->withoutMiddleware();

        Category::create([
            'name' => 'Electronics',
            'description' => 'All kinds of electronics'
        ]);

        Supplier::create([
            'name' => 'Tech Supplier',
            'email' => 'supplier@tech.com',
            'phone' => '123-456-7890',
            'address' => '123 Tech Lane',
        ]);
    }

    public function test_create_product()
    {
        $response = $this->post('/api/products/create', [
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => 100.00,
            'category_id' => Category::first()->id,
            'supplier_id' => Supplier::first()->id,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Product created successfully',
                 ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => 100.00,
            'category_id' => Category::first()->id,
            'supplier_id' => Supplier::first()->id,
        ]);
    }

    public function test_get_all_products()
    {
        Product::create([
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => 100.00,
            'category_id' => Category::first()->id,
            'supplier_id' => Supplier::first()->id,
        ]);

        $response = $this->get('/api/products/index');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'All products',
                 ])
                 ->assertJsonStructure([
                     'products' => [
                         [
                             'id',
                             'name',
                             'description',
                             'price',
                             'category_id',
                             'supplier_id',
                         ]
                    ],
                 ]);
    }

    public function test_update_product()
    {
        $product = Product::create([
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => 100.00,
            'category_id' => Category::first()->id,
            'supplier_id' => Supplier::first()->id,
        ]);

        $response = $this->put("/api/products/update/{$product->id}", [
            'name' => 'Updated Product',
            'description' => 'Updated description',
            'price' => 200.00,
            'category_id' => Category::first()->id,
            'supplier_id' => Supplier::first()->id,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Product updated successfully',
                 ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'description' => 'Updated description',
            'price' => 200.00,
            'category_id' => Category::first()->id,
            'supplier_id' => Supplier::first()->id,
        ]);
    }

    public function test_delete_product()
    {
        $product = Product::create([
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => 100.00,
            'category_id' => Category::first()->id,
            'supplier_id' => Supplier::first()->id,
        ]);

        $response = $this->deleteJson("/api/products/delete/{$product->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Product deleted successfully',
                 ]);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }
}
