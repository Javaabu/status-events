<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('status_events', function (Blueprint $table) {
            $table->id();

            $table->morphs('trackable');
            $table->nullableMorphs('user');
            $table->string('status')->index();
            $table->dateTime('event_at')->index();
            $table->string('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('status_events');
    }
};
