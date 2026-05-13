<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kycs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->date('birth_date');
            $table->string('nationality');
            $table->string('document_type');
            $table->string('document_number');
            $table->text('address')->nullable();
            $table->string('document_front');
            $table->string('document_back')->nullable();
            $table->string('selfie');
            $table->string('status')->default('not_submitted');
            $table->text('admin_notes')->nullable();
            $table->boolean('phone_verified')->default(false);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kycs');
    }
};
