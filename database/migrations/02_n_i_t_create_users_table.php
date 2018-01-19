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
            $table->engine = 'InnoDB';

            $table->increments('id')->unsigned();
            $table->string('nickname')->nullable();
            $table->string('email')->unique()->nullable();
            $table->boolean('subscribe')->nullable();
            $table->string('slug')->unique()->index();
            $table->string('timezone')->default('Europe/Moscow');
            $table->text('about')->nullable();

            $table->string('tag')->nullable();
            $table->string('tag_color')->nullable();

            $table->string('steam_username');
            $table->string('steamid');

            $table->string('truckersmp_username');
            $table->string('truckersmpid');

            $table->string('steam_avatar');
            $table->string('truckersmp_avatar')->nullable();
            $table->boolean('is_steam_avatar')->default(true);

            $table->json('options')->nullable();
            $table->jsonb('ban')->nullable();

            $table->boolean('rules_accepted')->default(false);

            $table->rememberToken();
            $table->timestampsTz();
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
