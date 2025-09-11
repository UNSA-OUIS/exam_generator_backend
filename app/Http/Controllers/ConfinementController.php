<?php

namespace App\Http\Controllers;

use App\Models\Confinement;
use Illuminate\Http\Request;
use App\Exports\BlocksExport;
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
            'started_at' => 'required|date',
            'ended_at' => 'required|date|after:started_at',
            'total' => 'required|integer|min:1'
        ]);

        $confinement = Confinement::create($validated);

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

    public function exportBlocks($confinementId)
    {
        return Excel::download(new BlocksExport($confinementId), 'blocks.xlsx');
    }
}
