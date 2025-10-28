<?php

namespace App\Http\Controllers;

use App\Models\Confinement;
use Illuminate\Http\Request;
use App\Exports\BlocksExport;
use App\Exports\ConfinementRequirementsExport;
use App\Exports\TextsExport;
use App\Models\ConfinementRequirement;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ConfinementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $confinements = Confinement::get();
        return response()->json($confinements);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'total' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $confinement = Confinement::create($request->except('total'));

            // Primer requerimiento es el total
            ConfinementRequirement::create([
                'confinement_id' => $confinement->id,
                'block_id' => null,
                'difficulty' => null,
                'n_questions' => $validated['total'],
                'parent_id' => null,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create confinement'], 500);
        }

        return response()->json($confinement, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Confinement $confinement)
    {
        return response()->json($confinement);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Confinement $confinement)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'started_at' => 'sometimes|required|date',
            'ended_at' => 'sometimes|required|date|after:started_at',
            'total' => 'sometimes|required|integer|min:1'
        ]);

        $confinement->update($validated);

        return response()->json($confinement);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Confinement $confinement)
    {
        $confinement->delete();
        return response()->json(null, 204);
    }

    public function exportRequirements($confinementId)
    {
        return Excel::download(new ConfinementRequirementsExport($confinementId), 'confinement_requirements.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportTexts($confinementId)
    {
        return Excel::download(new TextsExport($confinementId), 'confinement_texts.csv', \Maatwebsite\Excel\Excel::CSV);
    }
}
