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
        $faker = \Faker\Factory::create();
        for( $i = 0; $i < 50; $i++ ){
            User::create([
                'name' => $faker->firstName(),
                'Surname' => $faker->lastName(),
                'Login' => $faker->unique()->userName,
                'email' => $faker->unique()->freeEmail(),
                'password' => Hash::make('pass' . $i),
                'LastLogin' => $faker->dateTimeBetween($startDate = '-30 days', $endDate = 'now', $timezone = null),
            ]);
        }
    }
}
