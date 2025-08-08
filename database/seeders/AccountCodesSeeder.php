<?php

namespace Database\Seeders;

use App\Models\AccountCode;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AccountCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['accountcode' => '40202140-20-72-1', 'description' => 'Market Fees- Stall Rentals-MEAT-Current-Agdao'],
            ['accountcode' => '40202140-20-72-15', 'description' => 'Market Fees - Stall Rentals - Meat-Previous-Agdao'],
            ['accountcode' => '40202140-20-72-39', 'description' => 'Market Extension Rental-Economic Ent/Agdao'],
            ['accountcode' => '40202140-20-72-49', 'description' => 'Market Fines & Penalties-Economic Enterprises/Agdao'],

            ['accountcode' => '20401050-20-71-1', 'description' => 'Market Stall Rental Deposit-Econ. Enterprise/Bankerohan'],
            ['accountcode' => '40202140-20-71-15', 'description' => 'Market Fees - Stall Rentals - Meat-Previous-Bankerohan'],
            ['accountcode' => '40202140-20-71-39', 'description' => 'Market Extension Rental-Economic Ent/Bankerohan'],
            ['accountcode' => '40202140-20-71-49', 'description' => 'Market Fines & Penalties-Economic Enterprises/Bankerohan'],

            ['accountcode' => '40202140-20-73-1', 'description' => 'Market Stall Rental Deposit-Econ. Enterprise/Calinan'],
            ['accountcode' => '40202140-20-73-15', 'description' => 'Market Fees - Stall Rentals - Meat-Previous-Calinan'],
            ['accountcode' => '40202140-20-73-39', 'description' => 'Market Extension Rental-Economic Ent/Calinan'],
            ['accountcode' => '40202140-20-73-49', 'description' => 'Market Fines & Penalties-Economic Enterprises/Calinan'],

            ['accountcode' => '40202140-20-78-1', 'description' => 'Market Stall Rental Deposit-Econ. Enterprise/Bunawan'],
            ['accountcode' => '40202140-20-78-15', 'description' => 'Market Fees - Stall Rentals - Meat-Previous-Bunawan'],
            ['accountcode' => '40202140-20-78-39', 'description' => 'Market Extension Rental-Economic Ent/Bunawan'],
            ['accountcode' => '40202140-20-78-49', 'description' => 'Market Fines & Penalties-Economic Enterprises/Bunawan'],

            ['accountcode' => '20401050-20-75-1', 'description' => 'Market Stall Rental Deposit-Econ. Enterprise/Toril'],
            ['accountcode' => '40202140-20-75-15', 'description' => 'Market Fees - Stall Rentals - Meat-Previous-Toril'],
            ['accountcode' => '40202140-20-75-39', 'description' => 'Market Extension Rental-Economic Ent/Toril'],
            ['accountcode' => '40202140-20-75-49', 'description' => 'Market Fines & Penalties-Economic Enterprises/Toril'],

            ['accountcode' => '20401050-20-74-1', 'description' => 'Market Stall Rental Deposit-Econ. Enterprise/Mintal'],
            ['accountcode' => '40202140-20-74-15', 'description' => 'Market Fees - Stall Rentals - Meat-Previous-Mintal'],
            ['accountcode' => '40202140-20-74-39', 'description' => 'Market Extension Rental-Economic Ent/Mintal'],
            ['accountcode' => '40202140-20-74-49', 'description' => 'Market Fines & Penalties-Economic Enterprises/Mintal'],

            ['accountcode' => '40202140-20-77-1', 'description' => 'Market Stall Rental Deposit-Econ. Enterprise/Tibungco'],
            ['accountcode' => '40202140-20-77-15', 'description' => 'Market Fees - Stall Rentals - Meat-Previous-Tibungco'],
            ['accountcode' => '40202140-20-77-39', 'description' => 'Market Extension Rental-Economic Ent/Tibungco'],
            ['accountcode' => '40202140-20-77-49', 'description' => 'Market Fines & Penalties-Economic Enterprises/Tibungco'],

            ['accountcode' => '40202140-20-79-1', 'description' => 'Market Fees - Stall Rentals - Meat-Current-Lasang'],
            ['accountcode' => '40202140-20-79-15', 'description' => 'Market Fees - Stall Rentals - Meat-Previous-Lasang'],
            ['accountcode' => '40202140-20-79-39', 'description' => 'Market Extension Rental-Economic Ent/Lasang'],
            ['accountcode' => '40202140-20-79-49', 'description' => 'Market Fines & Penalties-Economic Enterprises/Lasang'],
        ];

        foreach ($data as $item) {
            AccountCode::create($item);
        }
    }
}
