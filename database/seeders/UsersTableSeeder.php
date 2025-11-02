<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a sample user (admin)
        $user = User::create([
            'username' => 'admin',
            'password' => Hash::make('password123'),
            'is_supervisor' => true,
            'is_admin' => true,
        ]);

        // Create user details for the same user
        UserDetail::create([
            'user_id' => $user->id,
            'employee_id' => 'EMP-0001',
            'firstname' => 'Rafael',
            'midinit' => 'F.',
            'lastname' => 'Fernandez',
            'office' => 'Market Office',
            'position' => 'System Administrator',
        ]);

        // Optionally, add another regular user
        $user2 = User::create([
            'username' => 'staff',
            'password' => Hash::make('staff123'),
            'is_supervisor' => false,
            'is_admin' => false,
        ]);

        UserDetail::create([
            'user_id' => $user2->id,
            'employee_id' => 'EMP-0002',
            'firstname' => 'Maria',
            'midinit' => 'D.',
            'lastname' => 'Santos',
            'office' => 'Market Operations',
            'position' => 'Staff',
        ]);

        // Optionally, add another regular user
        $user3 = User::create([
            'username' => 'supstaff',
            'password' => Hash::make('supstaff123'),
            'is_supervisor' => true,
            'is_admin' => false,
        ]);

        UserDetail::create([
            'user_id' => $user3->id,
            'employee_id' => 'EMP-0003',
            'firstname' => 'Maria',
            'midinit' => 'D.',
            'lastname' => 'Santos',
            'office' => 'Market Operations',
            'position' => 'supStaff',
        ]);
    }
}
