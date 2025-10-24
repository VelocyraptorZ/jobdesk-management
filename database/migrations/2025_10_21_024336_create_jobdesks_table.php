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
        Schema::create('jobdesks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('production_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('training_id')->nullable()->constrained()->nullOnDelete();
            $table->date('activity_date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->enum('activity_type', ['practical', 'theoretical', 'production', 'training']);
            $table->text('description');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobdesks', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropForeign(['production_id']);
            $table->dropForeign(['training_id']);
            $table->dropColumn(['course_id', 'production_id', 'training_id']);
            $table->dropColumn('status');
            $table->dropConstrainedForeignId('updated_by');
        });
    }
};
