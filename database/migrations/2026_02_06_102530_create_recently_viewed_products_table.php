<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function recentlyViewed()
    {
        return $this->belongsToMany(Product::class, 'recently_viewed_products')
                    ->withTimestamps()
                    ->withPivot('viewed_at')
                    ->orderByPivot('viewed_at', 'desc')
                    ->limit(10);
    }
    public function down(): void
    {
        Schema::dropIfExists('recently_viewed_products');
    }
};
