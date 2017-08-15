<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
//use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$today = Carbon\Carbon::now();
        //$today_date = Carbon::createFromFormat('Y-m-d',$today->toDateTimeString(),"Asia/Rangoon")->format('d-m-Y');
        $today = date('Y-m-d');
     	DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'position_id' => 1,
            'avatar' => 'sample.jpg',
            'join_date' => $today,
            'birthday' => $today,
            'location_id' => 1,
            'salary' => 0

        ]);
    }
}
