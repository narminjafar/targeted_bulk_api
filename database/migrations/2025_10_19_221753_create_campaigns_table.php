<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
           $table->id();

            // Əsas məlumatlar
            $table->uuid('uuid')->unique();
            $table->string('name')->nullable();
            $table->string('subject');
            $table->string('template_key');
            $table->text('content')->nullable();

            // Göndərişlə bağlı məlumatlar
            $table->string('from_email')->nullable();
            $table->foreignId('segment_id')->nullable()->constrained()->nullOnDelete();

            // Status və statistika
            $table->enum('status', ['draft', 'queued', 'sending', 'done', 'failed'])->default('draft');
            $table->unsignedInteger('total_recipients')->default(0);
            $table->unsignedInteger('sent_count')->default(0);
            $table->unsignedInteger('error_count')->default(0);

            // Cədvəl vaxtı və planlaşdırma
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
