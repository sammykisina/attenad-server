<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('course_intake', function (Blueprint $table) {
            $table->integer(column: 'intake_id');
            $table->integer(column: 'course_id');
        });
    }
};
