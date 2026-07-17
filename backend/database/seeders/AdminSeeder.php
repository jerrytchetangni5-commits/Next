<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        //Créer le premier administrateur s'il n'existe pas
        //sinon mettre ses informations à jour
        User::updateOrCreate(
            //critère de recherche
            [
                'email' => 'setontchet@gmail.com'
            ],

            //données à créer ou mettre à jour
            [
                'first_name' => env('ADMIN_FIRST_NAME', 'Tonton'),
                'last_name' => env('ADMIN_LAST_NAME', 'Dollars'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'Bebeto23')),
                'country' => env('ADMIN_COUNTRY', 'Benin'),
                'role' => env('ADMIN_ROLE', 'admin')
            ]
        );
    }
}
