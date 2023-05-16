<?php

use App\Models\Customer;
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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('make');
            $table->longText('notes')->nullable();
            $table->string('prod_year');
            $table->string('model');
            $table->string('regno')->nullable();
            $table->string('pnum')->nullable();
            $table->string('type');
            $table->date('lic_exp')->nullable();
            $table->foreignIdFor(Customer::class,'client_id')->default(1)->references('id')->on('customers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
