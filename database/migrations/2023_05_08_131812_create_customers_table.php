<?php

use App\Models\User;
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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sex');
            $table->boolean('vip')->default(false);
            $table->string('cell');
            $table->foreignIdFor(User::class, 'manager')->default(1)->references('id')->on('users');
            $table->string('ref')->nullable();
            $table->longText('notes')->nullable();
            $table->integer('risk_level');
            $table->date('dob')->nullable();
            $table->string('type');
            $table->string('secondary_phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
