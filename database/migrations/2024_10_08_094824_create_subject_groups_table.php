<?php

use App\Domain\Classifiers\Models\ClassifierOption;
use App\Domain\Subjects\Models\Subject;
use App\Domain\Syllabus\Models\Syllabus;
use App\Models\User;
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
        Schema::create('subject_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Syllabus::class)->index()->comment('semester')
                ->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('teacher_id')
                ->index()
                ->comment('biriktirgan o\'qituvchi')
                ->constrained('users') // Explicitly specify the table name here
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(Subject::class)->index()->comment('biriktirilgan fan')
                ->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('lesson',['lecture','practice','seminar','laboratory'])->comment('qanday darsligi: maruza,amaliy,..');
            $table->enum('flow',['yes','no'])->comment('dars oqimi');
            $table->enum('split_group',['yes','no'])->comment('guruh bo\'linishi');
            $table->integer('lesson_hour')->comment('dars soati');
            $table->foreignIdFor(ClassifierOption::class,'h_education_year');
            $table->integer('semester');
            $table->boolean('status')->default(false)->comment('o\'qituvchi ushbu fanini kiritgan yoki kirmaganligi uchun kerak');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_groups');
    }
};
