<?php

use Hyn\Tenancy\Tenant\Database\MySQLConnection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class HwsSslHostnamesDropHostnameId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(MySQLConnection::systemConnectionName())->table('ssl_hostnames', function (Blueprint $table) {
            // domain relation
            $table->dropColumn('hostname_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(MySQLConnection::systemConnectionName())->table('ssl_hostnames', function (Blueprint $table) {
            // domain relation
            $table->bigInteger('hostname_id')->unsigned();
        });
    }
}
