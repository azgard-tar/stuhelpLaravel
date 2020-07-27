<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $faker = \Faker\Factory::create();
        for( $i = 0; $i < 50; $i++ ){
            User::create([
                'name' => $faker->firstName(),
                'Surname' => $faker->lastName(),
                'Login' => $faker->userName,
                'email' => $faker->freeEmail(),
                'password' => Hash::make('pass' . $i),
                'id_Group' => $faker->numberBetween($min=0, $max=50),
                'LastLogin' => $faker->dateTimeBetween($startDate = '-30 days', $endDate = 'now', $timezone = null),
                'ShopInfo' => strval( $faker->regexify('(0|1){5}') ),
                'Coins' => 100,
                'id_Interface' => $faker->numberBetween($min=0, $max=50),
                'id_City' => $faker->numberBetween($min=0, $max=50),
                'id_Country' => $faker->numberBetween($min=0, $max=50)
            ]);
        }
    }
}
