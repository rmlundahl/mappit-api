<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignId('group_id')->default(1)->index();
            $table->boolean('is_group_admin')->default(false);
            $table->string('role', 64)->default('author')->index(); // 'author', 'editor', 'administrator'
            $table->rememberToken();
            $table->timestamps();
            $table->smallInteger('status_id')->unsigned()->default(1)->index(); // FK
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
