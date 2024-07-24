<?php

namespace App\Http\Controllers\api\roles;

use App\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class RoleController extends Controller
{
    // Create a new role
    public function create(Request $request)
    {
        try {
            $validateRole = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
            ]);

            if ($validateRole->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateRole->errors()
                ], 401);
            }

            $role = Role::create([
                'name' => $request->name,
                'description' => $request->description
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Role created successfully',
                'role' => $role,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
