<?php

use Hyn\Tenancy\Tenant\Database\MySQLConnection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HmtTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection(MySQLConnection::systemConnectionName())->hasTable('tenants')) {
            Schema::connection(MySQLConnection::systemConnectionName())->create(
                'tenants',
                function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('customer_no')->nullable();
                    $table->string('name');
                    $table->string('email');
                    $table->boolean('administrator')->default(false);

                    $table->bigInteger('reseller_id')->unsigned()->nullable();
                    $table->bigInteger('referer_id')->unsigned()->nullable();

                    $table->timestamps();
                    $table->softDeletes();

                    $table->index(['customer_no', 'name']);

                    $table->foreign('reseller_id')->references('id')->on('tenants')->onDelete('set null');
                    $table->foreign('referer_id')->references('id')->on('tenants')->onDelete('set null');
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
        if (Schema::connection(MySQLConnection::systemConnectionName())->hasTable('tenants')) {
            Schema::connection(MySQLConnection::systemConnectionName())->dropIfExists('tenants');
        }
    }
}
