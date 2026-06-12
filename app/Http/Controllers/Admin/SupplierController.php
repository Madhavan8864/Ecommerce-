<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        $suppliers = $query->latest()->paginate(20);
        
        $stats = [
            'total' => Supplier::count(),
            'active' => Supplier::where('status', 'active')->count(),
            'inactive' => Supplier::where('status', 'inactive')->count(),
        ];
        
        return view('admin.suppliers.index', compact('suppliers', 'stats'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'required|email|unique:suppliers',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'gst_number' => 'nullable|string|max:50',
            'payment_terms' => 'nullable|string|max:255',
            'lead_time' => 'nullable|integer|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Supplier::create($request->all());

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier added successfully!');
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $supplier->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'gst_number' => 'nullable|string|max:50',
            'payment_terms' => 'nullable|string|max:255',
            'lead_time' => 'nullable|integer|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $supplier->update($request->all());

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier updated successfully!');
    }

    public function destroy(Supplier $supplier)
    {
        // Check if supplier has products
        if ($supplier->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete supplier with associated products.'
            ], 400);
        }

        $supplier->delete();

        return response()->json([
            'success' => true,
            'message' => 'Supplier deleted successfully!'
        ]);
    }

    public function toggleStatus(Supplier $supplier)
    {
        $supplier->update([
            'status' => $supplier->status == 'active' ? 'inactive' : 'active'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Supplier status updated!'
        ]);
    }

    public function export()
    {
        $suppliers = Supplier::all();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="suppliers-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($suppliers) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Name', 'Company', 'Email', 'Phone', 'GST', 'City', 'Status', 'Products Count']);
            
            foreach ($suppliers as $supplier) {
                fputcsv($file, [
                    $supplier->name,
                    $supplier->company,
                    $supplier->email,
                    $supplier->phone,
                    $supplier->gst_number,
                    $supplier->city,
                    $supplier->status,
                    $supplier->products()->count()
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}