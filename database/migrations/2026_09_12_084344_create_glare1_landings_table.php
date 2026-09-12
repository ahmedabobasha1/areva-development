<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('glare1_landings', function (Blueprint $table) {
            $table->id();
            $table->json('hero_title');
            $table->json('hero_lead')->nullable();
            $table->json('cta_label')->nullable();
            $table->json('scroll_label')->nullable();
            $table->json('back_label')->nullable();
            $table->json('contact_eyebrow')->nullable();
            $table->json('contact_title')->nullable();
            $table->json('contact_lead')->nullable();
            $table->json('contact_banner')->nullable();
            $table->json('submit_label')->nullable();
            $table->json('trust_line')->nullable();
            $table->json('project_types')->nullable();
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('glare1_landings');
    }
};
