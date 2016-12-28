<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Model::unguard();

        $this->call('AdminUserTableSeeder');

        if (App::environment() === 'testing') {
            // Add More Seed For Testing
        }

        if (App::environment() === 'local') {
            $this->call(Seeds\Local\DatabaseSeeder::class);
        }

        Model::reguard();
    }
}
