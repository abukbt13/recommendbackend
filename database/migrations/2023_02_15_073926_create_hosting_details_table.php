<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hosting_details', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('company_name');
            $table->string('language');
            $table->string('type');
            $table->string('rating');
            $table->string('least_pricing_storage');
            $table->string('storage');
            $table->string('can_host_free');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hosting_details');
    }
};
