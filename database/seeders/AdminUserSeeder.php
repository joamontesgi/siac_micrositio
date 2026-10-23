<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $email = env('SIAC_ADMIN_EMAIL', 'siac_admin@unal.edu.co');
        $password = env('SIAC_ADMIN_PASSWORD', 'CambiarEstaClave123!');

        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => env('SIAC_ADMIN_NAME', 'Administrador SIAC'),
                'password' => Hash::make($password),
            ]
        );
    }
}
