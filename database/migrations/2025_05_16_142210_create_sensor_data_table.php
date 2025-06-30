<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSensorDataTable extends Migration
{
    public function up()
    {
        {
    Schema::dropIfExists('sensor_data');
}
    }

    public function down()
    {
        Schema::dropIfExists('sensor_data');
    }
}
