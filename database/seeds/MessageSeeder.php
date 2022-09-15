<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('messages')->truncate();

        DB::table('messages')->insert([
            [
                'sender_id' => 1,
                'recipient_id' =>2,
                'content' => 'halo min!'
            ],
            [
                'sender_id' => 1,
                'recipient_id' =>2,
                'content' => 'bisa bantu masalah saya?'
            ],
            [
                'sender_id' => 2,
                'recipient_id' =>1,
                'content' => 'boleh kak, ada yang bisa kami bantu?'
            ],
            [
                'sender_id' => 1,
                'recipient_id' => 2,
                'content' => 'PC Saya mati,Coding saya error, bisa dibantu perbaiki?'
            ],
            [
                'sender_id' => 2,
                'recipient_id' => 1,
                'content' => 'siap kak, mimin benerin segera.'
            ],

            // new customer chat ke staff
            [
                'sender_id' => 3,
                'recipient_id' => 2,
                'content' => 'halo min! saya customer 2'
            ],

            // new customer chat ke another customer
            [
                'sender_id' => 1,
                'recipient_id' => 3,
                'content' => 'halo another customer, how are you?'
            ],
        ]);
    }
}
