<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRusNameRowInCitiesAndCountries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->string('rus_name')->after('name');
        });

        Schema::table('countries', function (Blueprint $table) {
            $table->string('rus_name')->after('name');
        });

        Schema::table('dlcs', function (Blueprint $table) {
            $table->string('screen_name')->after('name')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn('rus_name');
        });

        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('rus_name');
        });

        Schema::table('dlcs', function (Blueprint $table) {
            $table->dropColumn('screen_name');
        });
    }
}
