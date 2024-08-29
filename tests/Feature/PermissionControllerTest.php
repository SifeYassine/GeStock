<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware();
    }

    public function test_create_permission()
    {
        $response = $this->post('/api/permissions/create', [
            'label' => 'view_posts',
            'description' => 'Permission to view posts',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Permission created successfully',
                 ]);

        $this->assertDatabaseHas('permissions', [
            'label' => 'view_posts',
            'description' => 'Permission to view posts',
        ]);
    }

    public function test_get_all_permissions()
    {
        Permission::create([
            'label' => 'edit_posts',
            'description' => 'Permission to edit posts',
        ]);

        $response = $this->get('/api/permissions/index');

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Permissions fetched successfully',
                 ])
                 ->assertJsonStructure([
                     'permissions' => [
                         [
                             'id',
                             'label',
                             'description',
                             'created_at',
                             'updated_at',
                         ]
                     ],
                 ]);
    }

    public function test_update_permission()
    {
        $permission = Permission::create([
            'label' => 'edit_posts',
            'description' => 'Permission to edit posts',
        ]);

        $response = $this->put("/api/permissions/update/{$permission->id}", [
            'label' => 'update_posts',
            'description' => 'Permission to update posts',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Permission updated successfully',
                 ]);

        $this->assertDatabaseHas('permissions', [
            'id' => $permission->id,
            'label' => 'update_posts',
            'description' => 'Permission to update posts',
        ]);
    }

    public function test_delete_permission()
    {
        $permission = Permission::create([
            'label' => 'delete_posts',
            'description' => 'Permission to delete posts',
        ]);

        $response = $this->deleteJson("/api/permissions/delete/{$permission->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Permission deleted successfully',
                 ]);

        $this->assertDatabaseMissing('permissions', [
            'id' => $permission->id,
        ]);
    }
}
