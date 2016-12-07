<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 500);
            $table->string('receiver_name', 150);
            $table->string('receiver_phone_number', 15);
            $table->text('pickup_address');
            $table->text('destination_address');
            $table->enum('status', ['new','on_progress', 'sent'])->default('new');
            $table->integer('id_customer')->unsigned();
            $table->integer('id_kurir')->unsigned()->nullable();
            $table->index(['id_kurir']);
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
        Schema::dropIfExists('items');
    }
}
