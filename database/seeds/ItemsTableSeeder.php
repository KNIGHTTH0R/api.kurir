<?php

use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('items')->insert([
            [
                'name' => 'samsung edge 7',
                'receiver_name' => 'saiful',
                'receiver_phone_number' => '+625254318976',
                'pickup_address' => 'gajahmada plaza, sawah besar, jakarta pusat',
                'destination_address' => 'pasaraya blok m, jakarta selatan',
                'status' => 'new',
            ],
            [
                'name' => 'macbook pro Retina 15 inch',
                'receiver_name' => 'sulaiman',
                'receiver_phone_number' => '+621309876549',
                'pickup_address' => 'Mangga 2 square, Jakarta pusat',
                'destination_address' => 'cinere mall, depok jawa barat',
                'status' => 'new',
            ],
            [
                'name' => 'nasi goreng bakmi gm',
                'receiver_name' => 'kartono',
                'receiver_phone_number' => '+621344553322',
                'pickup_address' => 'Bakmi GM blok m, seberang menara sentraya',
                'destination_address' => 'pom bensin radio dalam',
                'status' => 'new',
            ]
        ]);
    }
}
