<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $user = new User();
        $user->dui = '12345678-9';
        $user->nombre = 'Nombre de Usuario';
        $user->password = 'tu_contraseña_segura'; // Contraseña en texto plano
        $user->estado = 'activo';
        $user->save();

    }
}
