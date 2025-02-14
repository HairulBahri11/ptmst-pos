<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class customerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) { // Buat 10 customer random
            // Buat akun user
            $user = User::create([
                'username' => $faker->userName,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('12345678'),
                'role' => 'customer'
            ]);

            // Buat data customer
            Customer::create([
                'user_id' => $user->id,
                'kode' => 'CUST-' . strtoupper($faker->bothify('??###')), // Format kode random
                'name' => $faker->name,
                'telp' => $faker->phoneNumber,
                'alamat' => $faker->address,
                'status' => $faker->boolean ? 1 : 0, // Status random (1 aktif, 0 nonaktif)
            ]);
        }
    }
}
