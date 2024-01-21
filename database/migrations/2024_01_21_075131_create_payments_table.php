<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shareholder_id')->constrained(); 
            $table->date('due_date');
            $table->decimal('installment_amount', 10, 2);
            $table->date('payment_date')->nullable();
            $table->decimal('paid_amount', 10, 2)->nullable();
            $table->enum('status', ['Pending', 'Paid'])->default('Pending');           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
