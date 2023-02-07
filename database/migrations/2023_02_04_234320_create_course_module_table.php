<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('course_module', function (Blueprint $table) {
            $table->integer(column: 'course_id');
            $table->integer(column: 'module_id');
        });
    }
};
