<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStationsTable extends Migration
{
   public function up()
{
    Schema::create('stations', function (Blueprint $table) {
        $table->id();
        $table->integer('suhu')->nullable();           // data suhu
        $table->integer('kelembaban')->nullable();     // data kelembaban
        $table->integer('ph_air')->nullable();         // data pH air
        $table->integer('gas')->nullable();            // data gas
        $table->timestamp('waktu')->useCurrent();      // waktu pengambilan data
        $table->timestamps();                          // created_at dan updated_at
    });
}


    public function down()
    {
        Schema::dropIfExists('stations');
    }
}
