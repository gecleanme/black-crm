<?php

use App\Models\Contract;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *     value:null,
        premium: null,
        start_date:formattedStart,
        end_date:null,
        vendor:"",
        notes: ""

     */
    public function up(): void
    {
        Schema::create('contract_cycles', function (Blueprint $table) {
            $table->id();
            $table->unsignedFloat('value');
            $table->unsignedFloat('premium');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('vendor');
            $table->longText('notes')->nullable();
            $table->foreignId('contract_id')->nullable()->constrained()->cascadeOnDelete();
//            $table->foreignIdFor(Contract::class,'contract_id')->default(1)->references('id')->on('contracts');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_cycles');
    }
};
