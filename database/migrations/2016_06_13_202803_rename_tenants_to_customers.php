<?php

use Hyn\Tenancy\Tenant\Database\MySQLConnection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTenantsToCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection(MySQLConnection::systemConnectionName())->hasTable('tenants')) {
            Schema::connection(MySQLConnection::systemConnectionName())
                ->rename('tenants', 'customers');
            Schema::connection(MySQLConnection::systemConnectionName())
                ->table('websites', function (Blueprint $table) {
                    $table->dropForeign('websites_tenant_id_foreign');
                });
            Schema::connection(MySQLConnection::systemConnectionName())
                ->table('hostnames', function (Blueprint $table) {
                    $table->dropForeign('hostnames_tenant_id_foreign');
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
        if (Schema::connection(MySQLConnection::systemConnectionName())->hasTable('customers')) {
            Schema::connection(MySQLConnection::systemConnectionName())
                ->rename('customers', 'tenants');
            Schema::connection(MySQLConnection::systemConnectionName())
                ->table('websites', function (Blueprint $table) {
                    $table->renameColumn('customer_id', 'tenant_id');
                    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
                });
            Schema::connection(MySQLConnection::systemConnectionName())
                ->table('hostnames', function (Blueprint $table) {
                    $table->renameColumn('customer_id', 'tenant_id');
                    $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
                });
        }
    }
}
