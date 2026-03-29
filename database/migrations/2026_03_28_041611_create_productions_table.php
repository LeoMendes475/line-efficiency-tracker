<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->string('product_line');          // Nome da linha (ex: TV OLED)
            $table->integer('quantity_produced');     // Qtd total produzida
            $table->integer('quantity_defects');      // Qtd de defeitos
            $table->decimal('efficiency', 5, 2);      // Eficiência (ex: 98.50)
            $table->date('production_date');          // Data da produção
            $table->timestamps();                     // created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productions');
    }
}
