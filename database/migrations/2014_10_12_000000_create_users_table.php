<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid(column: 'uuid')->unique();
            $table->string(column: 'physical_card_id')->nullable()->unique();

            $table->string(column: 'email')->unique();
            $table->string(column: 'password');

            $table->foreignId(column: 'role_id')
                ->index()
                ->constrained();

            $table->string(column: "status");
            $table->string(column: "created_by");
            $table->string(column: "modified_by")->nullable();

            $table->string(column: "profile_picture_url")->nullable()->unique();

            $table->rememberToken();
            $table->timestamps();
        });
    }
};
