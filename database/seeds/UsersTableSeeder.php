<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'harry',
            'email' => 'harry@gmail.com',
            'phone_number' => '+6281397738684',
            'password' => \App\Helper\Hashed\hash_password('admin'),
            'type' => 'admin'
        ]);
    }
}
