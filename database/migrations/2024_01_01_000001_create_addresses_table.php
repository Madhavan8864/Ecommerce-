<?php 
 
use Illuminate\Database\Migrations\Migration; 
use Illuminate\Database\Schema\Blueprint; 
use Illuminate\Support\Facades\Schema; 
 
return new class extends Migration 
{ 
    public function up() 
    { 
        Schema::create('addresses', function (Blueprint $table) { 
            $table->id(); 
            $table->unsignedBigInteger('user_id'); 
            $table->enum('type', ['shipping', 'billing', 'both']); 
            $table->string('address_line_1'); 
            $table->string('address_line_2')->nullable(); 
            $table->string('city'); 
            $table->string('state'); 
            $table->string('zip_code'); 
            $table->string('country'); 
            $table->boolean('is_default')->default(false); 
            $table->timestamps(); 
 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); 
        }); 
    } 
 
    public function down() 
    { 
        Schema::dropIfExists('addresses'); 
    } 
}; 
