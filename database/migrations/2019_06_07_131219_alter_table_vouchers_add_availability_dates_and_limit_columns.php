<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableVouchersAddAvailabilityDatesAndLimitColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->string('available_from')->after('discount')->nullable();
            $table->string('available_until')->after('available_from')->nullable();
            $table->integer('limit')->after('available_until')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn('limit');
            $table->dropColumn('available_until');
            $table->dropColumn('available_from');
        });
    }
}
