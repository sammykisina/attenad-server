<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->uuid(column: 'uuid')->unique();

            $table->string(column: "name");
            $table->string(column: 'week');
            $table->string(column: "content_delivery_type");
            $table->string(column: 'tutorial_group')->nullable();

            $table->foreignId(column: "user_id")
                ->index()
                ->nullable()
                ->constrained();
            $table->foreignId(column: "intake_id")
                ->index()
                ->nullable()
                ->constrained();
            $table->foreignId(column: "course_id")
                ->index()
                ->nullable()
                ->constrained();
            $table->foreignId(column: "module_id")
                ->index()
                ->nullable()
                ->constrained();

            $table->timestamps();
        });
    }
};
