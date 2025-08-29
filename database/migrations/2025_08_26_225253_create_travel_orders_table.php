<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{


    public function up(): void
    {
        Schema::create('travel_orders', function (Blueprint $table) {
            $table->id();


            $table->foreignId('user_id')->constrained()->onDelete('cascade');


            $table->string('destination');
            $table->date('start_date');
            $table->date('end_date');


            $table->enum('status', ['solicitado', 'aprovado', 'cancelado'])->default('solicitado');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('travel_orders');
    }
};
