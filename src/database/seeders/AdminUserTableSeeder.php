<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class AdminUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('users')->delete();

        User::factory([
            'first_name'=> 'Admin',
            'last_name'=> 'Admin',
            'is_admin'=>1,
            'email'=> 'admin@buckhill.co.uk',
            'password'=> Hash::make('admin')
        ])->create();
    }
}
