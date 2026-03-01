<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => 'サンプル',
            'email' => 'sample1111@sample.com',
            'password' => Hash::make('password'),
        ];
        DB::table('users')->insert($param);

    }
}
