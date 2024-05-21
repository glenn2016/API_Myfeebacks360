<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        dd($row);
        return new User([
            //
            'name' => $row[0],
            'email' => $row[1],
            'password' => bcrypt($row[2]), // Assurez-vous de ne pas stocker de mots de passe en clair
        ]);
    }
}
