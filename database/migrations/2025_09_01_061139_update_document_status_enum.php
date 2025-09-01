<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // For MySQL, we need to use raw SQL for enum changes
        DB::statement("ALTER TABLE documents MODIFY COLUMN status ENUM('uploaded', 'text_extracted', 'processing', 'completed', 'failed') NOT NULL DEFAULT 'uploaded'");
    }

    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE documents MODIFY COLUMN status ENUM('uploaded', 'processing', 'completed', 'failed') NOT NULL DEFAULT 'uploaded'");
    }
};