<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $levels = Level::all();
        return response()->json($levels);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $maxStage = Level::max('stage');
        $validated['stage'] = $maxStage !== null ? $maxStage + 1 : 1;

        $level = Level::create($validated);

        return response()->json($level, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Level $level)
    {
        return response()->json($level);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Level $level)
    {
        $validated = $request->validate([
            'stage' => 'sometimes|required|integer|min:1',
            'name' => 'sometimes|required|string|max:255',
        ]);

        $level->update($validated);

        return response()->json($level);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Level $level)
    {
        // Check if the level has any blocks associated with it
        if ($level->blocks()->count() > 0) {
            return response()->json(['error' => 'Cannot delete level with associated blocks'], 400);
        }

        $level->delete();
        return response()->json(null, 204);
    }
}
