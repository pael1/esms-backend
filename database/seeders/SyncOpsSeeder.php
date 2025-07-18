<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SyncOpsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sync_ops')->insert([
            [
                'ornumber' => '0005744950C', 
                'ownerid' => '16000450',
                'months' => json_encode([1, 2, 3]),
                'created_at' => Carbon::now(),  // Set the current timestamp
                'updated_at' => Carbon::now()   // Set the current timestamp
            ],
            [
                'ornumber' => '0005777636C', 
                'ownerid' => '16000451',
                'months' => json_encode([1, 2, 3]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'ornumber' => '0005778158C', 
                'ownerid' => '16000452',
                'months' => json_encode([1, 2, 3]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'ornumber' => '0005777879C', 
                'ownerid' => '16000453',
                'months' => json_encode([1, 2, 3]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'ornumber' => '0005777609C', 
                'ownerid' => '16000454',
                'months' => json_encode([1, 2, 3]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);
    }
}
