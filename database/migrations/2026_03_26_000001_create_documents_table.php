<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('document_type', 64);
            $table->string('process', 64)->nullable();
            $table->string('institutional_level', 64)->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('dependency')->nullable();
            $table->string('topic')->nullable();
            $table->string('version', 32)->nullable();
            $table->string('status', 32)->default('vigente');
            $table->string('url')->nullable();
            $table->date('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};
