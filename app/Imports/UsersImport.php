<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'email' => $row['email'],
            'password' => bcrypt($row['password']), // Assurez-vous de ne pas stocker de mots de passe en clair
            'categorie_id' => $row['categorie_id'],
            'entreprise_id' => $row['entreprise_id'],
        ]);
    }
    public function headingRow(): int
    {
        return 1; // Si votre en-tête est sur la première ligne
    }
}
