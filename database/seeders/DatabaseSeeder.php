<?php

namespace Database\Seeders;

use App\Models\Participant;
use App\Models\User;
use Faker\Core\Number;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Testing\Fakes\Fake;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // for ($i = 0; $i < 11; $i++) {
        //     User::insert([
        //         'name' => $this->faker->name(),
        //         'email' => "admin@gmail.com",
        //         'password' => Hash::make('123456'),
        //     ]);
        //     Participant::insert([
        //         'name' => "Admin",
        //         'whatsapp' => Emai,
        //         'NIP' => Number::rand(3, 16),
        //         'keterangan' => str_random($i),
        //     ]);
        // }
    }
}
