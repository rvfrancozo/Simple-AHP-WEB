<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJudmentsTable extends Migration
{
    /**
     * Run the migrations.
     * php artisan make:migration create_judments_table --create="judments"
     * @return void
     */
    public function up()
    {
        Schema::create('judments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('user_email', 150);
            $table->integer('id_node');
            $table->integer('id_node1');
            $table->integer('id_node2');
            $table->double('score');
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
        Schema::dropIfExists('judments');
    }
}
