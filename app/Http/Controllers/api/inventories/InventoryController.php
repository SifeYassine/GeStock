<?php

namespace App\Http\Controllers\api\inventories;

use App\Models\Inventory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class InventoryController extends Controller
{
    // Create a new inventory
    public function create(Request $request)
    {   
        try {
            $validateInventory = Validator::make($request->all(), [
                'capacity' => 'required|integer',
                'current_stock' => 'required|integer',
                'location' => 'required|string|max:255',
            ]);

            if ($validateInventory->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateInventory->errors()
                ], 401);
            }

            $inventory = Inventory::create([
                'capacity' => $request->capacity,
                'current_stock' => $request->current_stock,
                'location' => $request->location,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Inventory created successfully',
                'inventory' => $inventory,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // Get all inventories
    public function index()
    {
        try {
            $inventories = Inventory::all();
            return response()->json([
                'status' => true,
                'message' => 'All inventories',
                'inventories' => $inventories
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // Update an inventory
    public function update(Request $request, $id)
    {
        try {
            $inventory = Inventory::find($id);

            if (!$inventory) {
                return response()->json([
                    'status' => false,
                    'message' => 'Inventory not found',
                ], 404);
            }

            $validateInventory = Validator::make($request->all(), [
                'capacity' => 'required|integer',
                'current_stock' => 'required|integer',
                'location' => 'required|string|max:255',
            ]);

            if ($validateInventory->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateInventory->errors()
                ], 401);
            }

            $inventory->update([
                'capacity' => $request->capacity,
                'current_stock' => $request->current_stock,
                'location' => $request->location,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Inventory updated successfully',
                'inventory' => $inventory,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    // Delete an inventory
    public function delete($id)
    {
        try {
            $inventory = Inventory::find($id);

            if (!$inventory) {
                return response()->json([
                    'status' => false,
                    'message' => 'Inventory not found',
                ], 404);
            }

            $inventory->delete();
            return response()->json([
                'status' => true,
                'message' => 'Inventory deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}        



