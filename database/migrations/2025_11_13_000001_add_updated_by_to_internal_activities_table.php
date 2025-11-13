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
        Schema::table('internal_activities', function (Blueprint $table) {
            if (! Schema::hasColumn('internal_activities', 'updated_by')) {
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internal_activities', function (Blueprint $table) {
            if (Schema::hasColumn('internal_activities', 'updated_by')) {
                $table->dropConstrainedForeignId('updated_by');
            }
        });
    }
};
