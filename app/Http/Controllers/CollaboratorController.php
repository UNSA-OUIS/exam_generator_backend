<?php

namespace App\Http\Controllers;

use App\Models\Collaborator;
use Illuminate\Http\Request;

class CollaboratorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collaborators = Collaborator::all();
        return response()->json($collaborators);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dni' => 'required|string|size:8|unique:collaborators,dni',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        $collaborator = Collaborator::create($validated);

        return response()->json($collaborator, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Collaborator $collaborator)
    {
        return response()->json($collaborator);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Collaborator $collaborator)
    {
        $validated = $request->validate([
            'dni' => 'required|string|size:8|unique:collaborators,dni',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        $collaborator->update($validated);

        return response()->json($collaborator);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collaborator $collaborator)
    {
        $collaborator->delete();
        return response()->json(null, 204);
    }
}
