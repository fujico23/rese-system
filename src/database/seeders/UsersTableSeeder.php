<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'role_id' => '1',
            'name' => '管理者太郎',
            'email' => 'test1@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('11111111'),
        ]);
        User::create([
            'role_id' => '2',
            'name' => '店舗代表者次郎',
            'email' => 'test2@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('22222222'),
        ]);
        User::create([
            'role_id' => '3',
            'name' => '利用者三郎',
            'email' => 'test3@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('33333333'),
        ]);
        User::create([
            'role_id' => '3',
            'name' => '利用者花子',
            'email' => 'test4@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('44444444'),
        ]);
    }
}
