<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('action_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // who did it
            $table->string('action_type'); // create, update, delete
            $table->string('model_type'); // full model class
            $table->unsignedBigInteger('model_id'); // model instance id

            $table->json('before_data')->nullable(); // old data
            $table->json('after_data')->nullable();  // new data
            $table->json('changes')->nullable();     // {"field": ["old","new"]}

            $table->string('route')->nullable(); // which route was hit
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('action_data');
    }
};
