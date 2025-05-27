<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function dashboard()
    {
        return view('auth.dashboard');
    }

    public function inventory_get()
    {
        $inventories = Inventory::all();
        return view('auth.inventory', compact('inventories'));
    }

    public function inventory_archive()
    {
        $inventories = Inventory::all();
        return view('auth.inventory_archive', compact('inventories'));
    }
}
