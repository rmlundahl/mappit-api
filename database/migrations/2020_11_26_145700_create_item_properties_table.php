<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_properties', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned(); // part of composite PK
            $table->string('language', 2); // part of composite PK
            $table->foreignId('item_id')->index();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->string('key', 255)->index();
            $table->mediumText('value');
            $table->timestamps();
            $table->smallInteger('status_id')->unsigned()->default(1)->index(); // FK
        });

        \DB::unprepared('ALTER TABLE `items` DROP PRIMARY KEY, ADD PRIMARY KEY ( `id` , `language` )');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_properties');
    }
}
