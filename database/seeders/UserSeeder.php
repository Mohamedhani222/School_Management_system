<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->delete();
        User::create([
            'name'=>'Mohamed',
            'email'=>'mohamedhacker740@gmail.com',
            'password'=>Hash::make('12345678')
        ]);
    }
}
