<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('functional_component', 64)->nullable()->after('title');
            $table->string('file_path')->nullable()->after('url');
            $table->string('original_filename')->nullable()->after('file_path');
        });
    }

    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['functional_component', 'file_path', 'original_filename']);
        });
    }
};
