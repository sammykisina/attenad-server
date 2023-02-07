<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->uuid(column: "uuid")->unique();

            $table->string(column: 'name');
            $table->string(column: 'code');
            $table->string(column: 'status');

            $table->string(column: 'created_by');
            $table->string(column: 'modified_by')->nullable();
            $table->timestamps();
        });
    }
};
