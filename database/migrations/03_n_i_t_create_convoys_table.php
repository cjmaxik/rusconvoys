<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConvoysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('convoys', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('slug')->unique()->index();
            $table->boolean('pinned')->default(false);

            $table->integer('user_id')->unsigned();
            $table->integer('server_id')->unsigned();

            $table->timestampTz('meeting_datetime')->nullable();
            $table->timestampTz('leaving_datetime')->nullable();

            $table->integer('start_town_id')->unsigned();
            $table->string('start_place');

            $table->integer('finish_town_id')->unsigned();
            $table->string('finish_place');

            $table->string('stops')->nullable();

            $table->string('background_url')->nullable();
            $table->string('background_url_safe')->nullable();

            $table->string('map_url')->nullable();
            $table->string('map_url_safe')->nullable();

            $table->string('voice_description');
            $table->text('description');
            $table->string('cancelled_message')->nullable();

            $table->enum('status', [
                'draft',
                'open',
                'meeting',
                'on_way',
                'voting',
                'closed',
                'cancelled',
            ])->default('open');
            $table->boolean('mailed')->default(false);

            $table->softDeletes();
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
        Schema::drop('convoys');
    }
}
