<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Inventory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InventoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    
        $this->withoutMiddleware();
    }

    public function test_create_inventory()
    {
        $response = $this->postJson('/api/inventories/create', [
            'capacity' => 100,
            'current_stock' => 50,
            'location' => 'Warehouse A',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Inventory created successfully',
                 ]);

        $this->assertDatabaseHas('inventories', [
            'capacity' => 100,
            'current_stock' => 50,
            'location' => 'Warehouse A',
        ]);
    }

    public function test_get_all_inventories()
    {
        Inventory::create([
            'capacity' => 100,
            'current_stock' => 50,
            'location' => 'Warehouse A',
        ]);

        Inventory::create([
            'capacity' => 200,
            'current_stock' => 150,
            'location' => 'Warehouse B',
        ]);

        $response = $this->getJson('/api/inventories/index');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'All inventories',
                 ])
                 ->assertJsonStructure([
                    'inventories' => [
                        [
                            'id',
                            'capacity',
                            'current_stock',
                            'location',
                            'created_at',
                            'updated_at',
                        ]
                    ],
                 ]);
    }

    public function test_update_inventory()
    {
        $inventory = Inventory::create([
            'capacity' => 100,
            'current_stock' => 50,
            'location' => 'Warehouse A',
        ]);

        $response = $this->putJson("/api/inventories/update/{$inventory->id}", [
            'capacity' => 150,
            'current_stock' => 100,
            'location' => 'Warehouse B',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Inventory updated successfully',
                 ]);

        $this->assertDatabaseHas('inventories', [
            'id' => $inventory->id,
            'capacity' => 150,
            'current_stock' => 100,
            'location' => 'Warehouse B',
        ]);
    }

    public function test_delete_inventory()
    {
        $inventory = Inventory::create([
            'capacity' => 100,
            'current_stock' => 50,
            'location' => 'Warehouse A',
        ]);

        $response = $this->deleteJson("/api/inventories/delete/{$inventory->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Inventory deleted successfully',
                 ]);

        $this->assertDatabaseMissing('inventories', [
            'id' => $inventory->id,
        ]);
    }
}
