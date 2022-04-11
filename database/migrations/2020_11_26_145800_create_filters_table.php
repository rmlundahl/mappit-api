<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filters', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned(); // part of composite PK
            $table->string('language', 2); // part of composite PK
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name', 255);
            $table->string('slug', 255)->index();
            $table->timestamps();
            $table->smallInteger('status_id')->unsigned()->default(1)->index(); // FK
        });

        \DB::unprepared('ALTER TABLE `filters` DROP PRIMARY KEY, ADD PRIMARY KEY ( `id` , `language` )');
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
