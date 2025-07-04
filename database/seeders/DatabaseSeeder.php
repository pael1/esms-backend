<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\UserDetail;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {

            $user = User::create([
                // 'uuid' => $faker->uuid(),
                'username' => $faker->firstName(),
                'email' => $faker->email(),
                'password' => Hash::make('password'),
            ]);

            UserDetail::create([
                'user_id' => $user->id,
                'address' => $faker->address(),
                'birthday' => $faker->date(),
            ]);
        }
    }
}
