<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Services\ScholarshipImporter;

class ScholarshipSyncController extends Controller
{
    public function handle(Request $request)
    {
        // Vérification de sécurité
        if ($request->header('X-Cron-Secret') !== env('CRON_SECRET')) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $scholarships = $request->input('scholarships');
        if (!is_array($scholarships)) {
            return response()->json(['error' => 'Données invalides'], 400);
        }

        // Sauvegarde temporaire pour l'importer
        $tempPath = storage_path('app/temp-sync.json');
        File::put($tempPath, json_encode($scholarships));

        try {
            $importer = app(\App\Services\ScholarshipImporter::class);
            $stats = $importer->import($tempPath);
            $importer->removeExpiredScholarships();
            File::delete($tempPath);

            return response()->json(['message' => 'Succès', 'stats' => $stats], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}