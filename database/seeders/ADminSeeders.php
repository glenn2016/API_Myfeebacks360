<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class ADminSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Créez un rôle d'administrateur s'il n'existe pas
        $adminRole = Role::firstOrCreate(['nom' => 'SuperAdmin']);

        // Créez un utilisateur administrateur
        $admin = User::create([
                'nom' => 'Admin Name',
                'prenom' => 'Admin Name',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'), // Assurez-vous de hacher le mot de passe
                ]);
        
        // Attachez le rôle d'administrateur à l'utilisateur administrateur
        $admin->roles()->attach($adminRole->id);
    }
}
