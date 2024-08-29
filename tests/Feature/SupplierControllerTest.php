<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SupplierControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    
        $this->withoutMiddleware();
    }

    public function test_create_supplier()
    {
        $response = $this->postJson('/api/suppliers/create', [
            'name' => 'Supplier Inc.',
            'email' => 'contact@supplierinc.com',
            'phone' => '123-456-7890',
            'address' => '789 Elm Street',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Supplier created successfully',
                 ]);

        $this->assertDatabaseHas('suppliers', [
            'name' => 'Supplier Inc.',
            'email' => 'contact@supplierinc.com',
            'phone' => '123-456-7890',
            'address' => '789 Elm Street',
        ]);
    }

    public function test_get_all_suppliers()
    {
        Supplier::create([
            'name' => 'Supplier Inc.',
            'email' => 'contact@supplierinc.com',
            'phone' => '123-456-7890',
            'address' => '789 Elm Street',
        ]);

        Supplier::create([
            'name' => 'Another Supplier',
            'email' => 'info@anothersupplier.com',
            'phone' => '098-765-4321',
            'address' => '123 Oak Avenue',
        ]);

        $response = $this->getJson('/api/suppliers/index');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'All suppliers',
                 ])
                 ->assertJsonStructure([
                    'suppliers' => [
                        [
                            'id',
                            'name',
                            'email',
                            'phone',
                            'address',
                            'created_at',
                            'updated_at',
                        ]
                    ],
                 ]);
    }

    public function test_update_supplier()
    {
        $supplier = Supplier::create([
            'name' => 'Supplier Inc.',
            'email' => 'contact@supplierinc.com',
            'phone' => '123-456-7890',
            'address' => '789 Elm Street',
        ]);

        $response = $this->putJson("/api/suppliers/update/{$supplier->id}", [
            'name' => 'Updated Supplier Inc.',
            'email' => 'updated@supplierinc.com',
            'phone' => '321-654-0987',
            'address' => '456 Pine Road',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Supplier updated successfully',
                 ]);

        $this->assertDatabaseHas('suppliers', [
            'id' => $supplier->id,
            'name' => 'Updated Supplier Inc.',
            'email' => 'updated@supplierinc.com',
            'phone' => '321-654-0987',
            'address' => '456 Pine Road',
        ]);
    }

    public function test_delete_supplier()
    {
        $supplier = Supplier::create([
            'name' => 'Supplier Inc.',
            'email' => 'contact@supplierinc.com',
            'phone' => '123-456-7890',
            'address' => '789 Elm Street',
        ]);

        $response = $this->deleteJson("/api/suppliers/delete/{$supplier->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Supplier deleted successfully',
                 ]);

        $this->assertDatabaseMissing('suppliers', [
            'id' => $supplier->id,
        ]);
    }
}
