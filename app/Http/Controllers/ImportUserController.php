<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class ImportUserController extends Controller
{
    //
    public function import(Request $request)
    {
        // Valider que le fichier est présent et de type Excel
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            // Importer les données du fichier
            Excel::import(new UsersImport, $request->file('file'));

            // Retourner une réponse de succès
            return response()->json(['message' => 'Importation réussie'], 200);

        } catch (Exception $e) {
            // Retourner une réponse d'erreur avec le message d'exception
            return response()->json(['message' => 'Erreur lors de l\'importation: ' . $e->getMessage()], 500);
        }
    }
}