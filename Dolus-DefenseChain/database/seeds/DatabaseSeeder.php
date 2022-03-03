<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use app\database\seeds\LoginTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        $this->call('UserTableSeeder');
    }
}
