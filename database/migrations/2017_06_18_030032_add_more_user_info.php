<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreUserInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            
            //profile image
            $table->text('avatar')->default("");

            //office info
            $table->integer('position_id')->default("");
            $table->date('join_date')->default("");
            $table->string('office_email')->default("");

            //bank info
            $table->string('bank_account')->default("");
            $table->string('bank_name')->default("");

            //leave
            $table->float('no_of_leave')->default(0);

            //personal
            $table->string('mobile_no')->default("");
            $table->string('personal_email')->default("");
            $table->date('birthday')->default("");

            //social network
            $table->string('github')->default("");
            $table->string('twitter')->default("");


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn('avatar');
            $table->dropColumn('position_id');
            $table->dropColumn('join_date');
            
            $table->dropColumn('bank_account');
            $table->dropColumn('bank_name');
            $table->dropColumn('no_of_leave');
            $table->dropColumn('mobile_no');
            $table->dropColumn('personal_email');
            $table->dropColumn('office_email');
            $table->dropColumn('birthday');

            $table->dropColumn('github');
            $table->dropColumn('twitter');
        });
    }
}
