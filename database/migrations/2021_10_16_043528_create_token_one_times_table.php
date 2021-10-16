<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTokenOneTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('token_one_times', function (Blueprint $table) {
            $table->string('id', 75)->nullable($value = false)->unique()->index();
            $table->string('token', 350)->nullable($value = false)->unique()->index();
            $table->boolean('revoked')->default(false);
            $table->timestamp('expired_at')->nullable($value = false);
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
        Schema::dropIfExists('token_one_times');
    }
}
