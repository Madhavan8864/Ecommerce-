<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product']);
        
        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by rating
        if ($request->has('rating') && $request->rating != 'all') {
            $query->where('rating', $request->rating);
        }
        
        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('comment', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $reviews = $query->latest()->paginate(20);
        
        // Statistics
        $stats = [
            'total' => Review::count(),
            'pending' => Review::where('status', 'pending')->count(),
            'approved' => Review::where('status', 'approved')->count(),
            'rejected' => Review::where('status', 'rejected')->count(),
            'avg_rating' => Review::avg('rating'),
        ];
        
        return view('admin.reviews.index', compact('reviews', 'stats'));
    }
    
    public function show(Review $review)
    {
        $review->load(['user', 'product']);
        return view('admin.reviews.show', compact('review'));
    }
    
    public function approve(Review $review)
    {
        $review->update(['status' => 'approved']);
        
        // Update product rating
        $review->product->updateRating();
        
        return response()->json([
            'success' => true,
            'message' => 'Review approved successfully!'
        ]);
    }
    
    public function reject(Review $review)
    {
        $review->update(['status' => 'rejected']);
        
        return response()->json([
            'success' => true,
            'message' => 'Review rejected successfully!'
        ]);
    }
    
    public function destroy(Review $review)
    {
        $review->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully!'
        ]);
    }
    
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:reviews,id'
        ]);
        
        $count = 0;
        
        foreach ($request->ids as $id) {
            $review = Review::find($id);
            
            switch ($request->action) {
                case 'approve':
                    $review->update(['status' => 'approved']);
                    $review->product->updateRating();
                    $count++;
                    break;
                case 'reject':
                    $review->update(['status' => 'rejected']);
                    $count++;
                    break;
                case 'delete':
                    $review->delete();
                    $count++;
                    break;
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "{$count} reviews {$request->action}d successfully!"
        ]);
    }
}