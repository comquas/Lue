<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
             //profile image
            $table->text('avatar')->nullable();

            //office info
            $table->integer('position_id')->default(0);
            $table->date('join_date');
            $table->string('office_email')->default("");

            //bank info
            $table->string('bank_name')->default("");
            $table->string('bank_account')->default("");
            

            //salary
            $table->integer('salary')->nullable();

            //leave
            $table->float('no_of_leave')->default(0);
             $table->float('sick_leave')->default(0);


            //personal
            $table->string('mobile_no')->default("");
            $table->string('personal_email')->default("");
            $table->date('birthday');

            //social network
            $table->string('github')->default("");
            $table->string('twitter')->default("");
            //slack
            $table->string('slack')->nullable();

            //location
            $table->integer('location_id');

            //supervisor
            $table->integer('supervisor_id')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
