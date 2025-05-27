<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoriesController extends Controller
{
    public function inventory_post(Request $request)
    {
        try {
            $request->validate([
                'itemName' => 'required|string|max:255',
                'brandName' => 'required|string|max:255',
                'itemCategory' => 'required|string|max:255',
                'itemQuantity' => 'required|integer|min:1',
                'itemPrice' => 'required|numeric|min:0',
            ]);

            Inventory::create([
                'item_name' => $request->input('itemName'),
                'brand_name' => $request->input('brandName'),
                'category' => $request->input('itemCategory'),
                'quantity' => $request->input('itemQuantity'),
                'max_quantity' => $request->input('itemQuantity'),
                'price' => $request->input('itemPrice'),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('inventory_get')->with('error', 'Failed to add item: ' . $e->getMessage());
        }
        return redirect()->route('inventory_get')->with('success', 'Item added successfully.');
    }

    public function inventory_update(Request $request, $id)
    {
        try {
            $request->validate([
                'editItemName' => 'required|string|max:255',
                'editBrandName' => 'required|string|max:255',
                'editItemCategory' => 'required|string|max:255',
                'editItemQuantity' => 'required|integer|min:1',
                'editItemPrice' => 'required|numeric|min:0',
            ]);

            $inventory = Inventory::findOrFail($id);
            $inventory->update([
                'item_name' => $request->input('editItemName'),
                'brand_name' => $request->input('editBrandName'),
                'category' => $request->input('editItemCategory'),
                'quantity' => $request->input('editItemQuantity'),
                'price' => $request->input('editItemPrice'),
            ]);
        } catch (\Exception $e) {
            return redirect()->route('inventory_get')->with('error', 'Failed to update item: ' . $e->getMessage());
        }
        return redirect()->route('inventory_get')->with('success', 'Item updated successfully.');
    }

    public function inventory_archive($id)
    {
        try {
            $inventory = Inventory::findOrFail($id);
            $inventory->is_archived = true;
            $inventory->save();
        } catch (\Exception $e) {
            return redirect()->route('inventory_get')->with('error', 'Failed to archive item: ' . $e->getMessage());
        }
        return redirect()->route('inventory_get')->with('success', 'Item archived successfully.');
    }

    public function inventory_restore($id)
    {
        try {
            $inventory = Inventory::findOrFail($id);
            $inventory->is_archived = false;
            $inventory->save();
        } catch (\Exception $e) {
            return redirect()->route('inventory_archive')->with('error', 'Failed to archive item: ' . $e->getMessage());
        }
        return redirect()->route('inventory_archive')->with('success', 'Item Restore successfully.');
    }

    public function inventory_delete($id)
    {
        try {
            $inventory = Inventory::findOrFail($id);
            $inventory->delete();
        } catch (\Exception $e) {
            return redirect()->route('inventory_get')->with('error', 'Failed to delete item: ' . $e->getMessage());
        }
        return redirect()->route('inventory_get')->with('success', 'Item deleted successfully.');
    }
}
