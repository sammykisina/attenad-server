<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('attendance_user', function (Blueprint $table) {
            $table->integer(column: 'attendance_id');
            $table->integer(column: 'user_id');
        });
    }
};
