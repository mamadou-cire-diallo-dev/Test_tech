<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Jobs\ExportExpensesJob;
use App\Models\Export;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExportController extends Controller
{
    public function store(Request $request)
    {
        $this->authorize('create', Export::class);

        $validated = $request->validate([
            'status' => 'required|in:APPROVED',
            'period' => 'required|date_format:Y-m',
        ]);

        $export = Export::create([
            'user_id' => Auth::id(),
            'status' => 'PENDING',
            'meta' => [
                'status' => $validated['status'],
                'period' => $validated['period'],
            ],
        ]);

        ExportExpensesJob::dispatch($export);



        return response()->json([
            'message' => 'En cours d\'exportation',
            'export_id' => $export->id,
        ], 202);
    }

    public function show(Export $export)
    {
        $this->authorize('view', $export);

        if ($export->status === 'READY' && $export->file_path) {

            $url = Storage::disk('public')->url($export->file_path);
            return response()->json([
                'status' => $export->status,
                'download_url' => $url,
            ]);
        } elseif ($export->status === 'PENDING') {
            return response()->json([
                'status' => $export->status,
                'message' => 'Export en cours ',
            ], 202);
        } elseif ($export->status === 'FAILED') {
            return response()->json([
                'status' => $export->status,
                'message' => 'L\'exportation à  échouer',
            ], 500);
        }

        return response()->json([
            'status' => $export->status,
            'message' => 'fichier a exporter est introuvable ou n\'est pas prêt ! '
        ], 404);
    }
}
