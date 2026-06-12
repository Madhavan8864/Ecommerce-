<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        $query = Warehouse::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        $warehouses = $query->latest()->paginate(20);
        
        $stats = [
            'total' => Warehouse::count(),
            'active' => Warehouse::where('status', 'active')->count(),
            'inactive' => Warehouse::where('status', 'inactive')->count(),
            'total_capacity' => Warehouse::sum('capacity'),
        ];
        
        return view('admin.warehouses.index', compact('warehouses', 'stats'));
    }

    public function create()
    {
        return view('admin.warehouses.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:warehouses',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'capacity' => 'nullable|integer|min:0',
            'temperature_controlled' => 'boolean',
            'hazmat_certified' => 'boolean',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Warehouse::create($request->all());

        return redirect()->route('admin.warehouses.index')
            ->with('success', 'Warehouse added successfully!');
    }

    public function edit(Warehouse $warehouse)
    {
        return view('admin.warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:warehouses,code,' . $warehouse->id,
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'capacity' => 'nullable|integer|min:0',
            'temperature_controlled' => 'boolean',
            'hazmat_certified' => 'boolean',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $warehouse->update($request->all());

        return redirect()->route('admin.warehouses.index')
            ->with('success', 'Warehouse updated successfully!');
    }

    public function destroy(Warehouse $warehouse)
    {
        // Check if warehouse has stock
        if ($warehouse->stockMovements()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete warehouse with stock movements.'
            ], 400);
        }

        $warehouse->delete();

        return response()->json([
            'success' => true,
            'message' => 'Warehouse deleted successfully!'
        ]);
    }

    public function toggleStatus(Warehouse $warehouse)
    {
        $warehouse->update([
            'status' => $warehouse->status == 'active' ? 'inactive' : 'active'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Warehouse status updated!'
        ]);
    }

    public function getStock(Warehouse $warehouse)
    {
        $stock = $warehouse->stockMovements()
            ->with('product')
            ->latest()
            ->paginate(20);

        return view('admin.warehouses.stock', compact('warehouse', 'stock'));
    }
}