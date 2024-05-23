<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exceptions\DuplicateEmailException;
use Illuminate\Validation\ValidationException;
use Exception;

class ImportUserController extends Controller
{
    //
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            $import = new UsersImport;
            Excel::import($import, $request->file('file'));

            $message = 'Importation réussie';
            $status = 200;
            
            if (isset($errors['password']) && in_array('The password field must be at least 6 characters.', $errors['password'])) {
                return response()->json(['message' => 'Le mot de passe est trop court'], 423);
            }
            if (!empty($import->errors)) {
                $message = 'Importation partielle avec erreurs';
                $status = 422;
            }
            return response()->json([
                'message' => $message,
                'errors' => $import->errors,
                'successes' => $import->successes,
            ], $status);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->errors()], 422);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Erreur lors de l\'importation: ' . $e->getMessage()], 500);
        }
    }
}