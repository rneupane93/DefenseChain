// app/database/seeds/LoginTableSeeder.php

<?php

use Illuminate\Auth\Events\Login;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoginTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('login')->delete();
        Login::create(array(
            'username' => 'ramya',
            'password' => Hash::make('ramya'),
        ));
    }

}
