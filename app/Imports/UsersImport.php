<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Role;
use App\Models\Categorie;
use App\Models\Entreprise;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;


class UsersImport implements ToCollection,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public $errors = [];
    public $successes = [];
    
    public function collection(Collection $rows)
    {
        $participantRole = Role::firstOrCreate(['nom' => 'Participant']);
        $currentUserId = Auth::id();

        foreach ($rows as $row) {
            try {
                // Ignore les lignes avec une valeur nulle
                if ($row->contains(null)) {
                    $this->errors[] = 'Ligne ignorée à cause de valeurs nulles: ' . json_encode($row);
                    continue;
                }

                // Valider les données de la ligne
                $this->validateRow($row);

                // Vérifiez si l'e-mail existe
                if (User::where('email', $row['email'])->exists()) {
                    $this->errors[] = "L'email {$row['email']} existe déjà.";
                    continue; // Skip this row
                }

                // Vérifiez si la catégorie existe pour cet utilisateur, sinon créez-la
                $categorie = Categorie::where('nom', $row['categorie'])
                    ->where('usercreate', $currentUserId)
                    ->first();
                if (!$categorie) {
                    $categorie = Categorie::create([
                        'nom' => $row['categorie'],
                        'usercreate' => $currentUserId,
                    ]);
                }

                // Vérifiez si l'entreprise existe pour cet utilisateur, sinon créez-la
                $entreprise = Entreprise::where('nom', $row['entreprise'])
                    ->where('usercreate', $currentUserId)
                    ->first();
                if (!$entreprise) {
                    $entreprise = Entreprise::create([
                        'nom' => $row['entreprise'],
                        'usercreate' => $currentUserId,
                    ]);
                }

                // Créer un utilisateur
                $user = User::create([
                    'nom' => $row['nom'],
                    'prenom' => $row['prenom'],
                    'email' => $row['email'],
                    'password' => bcrypt($row['password']),
                    'categorie_id' => $categorie->id,
                    'entreprise_id' => $entreprise->id,
                    'usercreate' => $currentUserId,
                ]);

                // Attacher le rôle « Participant » à l'utilisateur
                $user->roles()->attach($participantRole);

                $this->successes[] = 'Utilisateur créé avec succès: ' . json_encode($user);
            } catch (ValidationException $e) {
                $this->errors[] = 'Erreur de validation: ' . json_encode($e->errors());
                continue; // Sauter cette ligne
            } catch (Exception $e) {
                $this->errors[] = 'Erreur lors de l\'importation: ' . $e->getMessage();
                continue; // Passer cette ligne
            }
        }
    }

    protected function validateRow(Collection $row)
    {
        $validator = Validator::make($row->toArray(), [
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
            'categorie' => 'required|string',
            'entreprise' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function headingRow(): int
    {
        return 1; // En supposant que votre en-tête soit sur la première ligne
    }
}
