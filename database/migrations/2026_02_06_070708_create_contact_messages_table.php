<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->enum('status', ['unread', 'read', 'replied', 'spam'])->default('unread');
            $table->text('admin_notes')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->unsignedBigInteger('replied_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('replied_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['status', 'created_at']);
            $table->index('email');
        });
    }

    public function down()
    {
        Schema::dropIfExists('contact_messages');
    }
};