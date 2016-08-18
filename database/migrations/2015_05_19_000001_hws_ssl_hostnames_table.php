<?php

use Hyn\Tenancy\Tenant\Database\MySQLConnection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class HwsSslHostnamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection(MySQLConnection::systemConnectionName())->hasTable('ssl_hostnames')) {
            Schema::connection(MySQLConnection::systemConnectionName())->create('ssl_hostnames',
                function (Blueprint $table) {
                    $table->bigIncrements('id');
                    // certificate id
                    $table->bigInteger('ssl_certificate_id')->unsigned();
                    // domain relation
                    $table->bigInteger('hostname_id')->unsigned();

                    // certificate
                    $table->string('hostname');

                    // timestaps
                    $table->timestamps();
                    $table->softDeletes();

                    // index
                    $table->index('hostname');
                    $table->index('hostname_id');
                    $table->index('ssl_certificate_id');
                    // relations
                    $table->foreign('ssl_certificate_id')->references('id')->on('ssl_certificates')->onDelete('cascade');
                });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::connection(MySQLConnection::systemConnectionName())->hasTable('ssl_hostnames')) {
            Schema::connection(MySQLConnection::systemConnectionName())->dropIfExists('ssl_hostnames');
        }
    }
}
