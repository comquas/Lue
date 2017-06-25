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
            $table->text('avatar');

            //office info
            $table->integer('position_id');
            $table->date('join_date');

            //bank info
            $table->string('bank_account');
            $table->string('bank_name');

            //leave
            $table->float('no_of_leave');

            //personal
            $table->string('mobile_no');
            $table->string('personal_email');
            $table->string('office_email');
            $table->date('birthday');

            //social network
            $table->string('github');
            $table->string('twitter');


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
            $table->dropColumn('position');
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
