<?php

use Hyn\Tenancy\Tenant\Database\MySQLConnection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HmtWebsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection(MySQLConnection::systemConnectionName())->hasTable('websites')) {
            Schema::connection(MySQLConnection::systemConnectionName())->create(
                'websites',
                function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('tenant_id')->unsigned();
                    $table->string('identifier');
                    $table->timestamps();
                    $table->softDeletes();

                    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');

                    $table->index('identifier');
                    $table->unique('identifier');
                }
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::connection(MySQLConnection::systemConnectionName())->hasTable('websites')) {
            Schema::connection(MySQLConnection::systemConnectionName())->dropIfExists('websites');
        }
    }
}
