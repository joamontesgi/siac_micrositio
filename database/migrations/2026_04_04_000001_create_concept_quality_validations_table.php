<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('concept_quality_validations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('dependency')->nullable();
            $table->string('role')->nullable();
            $table->string('answer_p1', 8);
            $table->string('answer_p2', 8);
            $table->string('answer_p3', 8);
            $table->text('observation_p1')->nullable();
            $table->text('observation_p2')->nullable();
            $table->text('observation_p3')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('concept_quality_validations');
    }
};
