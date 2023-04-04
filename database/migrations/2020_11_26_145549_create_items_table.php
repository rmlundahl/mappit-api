<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id')->unsigned(); // part of composite PK
            $table->string('language', 2); // part of composite PK
            $table->tinyInteger('item_type_id')->default(10); // 10 = item, 20 = page
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->string('external_id', 255)->nullable()->index(); // only required if data is imported from external source, to keep track of updates on existing records
            $table->string('name', 255);
            $table->string('slug', 255)->index();
            $table->mediumText('content')->nullable();
            $table->foreignId('user_id')->index(); // FK
            $table->timestamps();
            $table->smallInteger('status_id')->unsigned()->default(1)->index(); // FK
            $table->unique(['slug', 'language']);
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
        Schema::dropIfExists('items');
    }
}
