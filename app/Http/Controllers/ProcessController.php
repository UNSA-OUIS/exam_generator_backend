<?php

namespace App\Http\Controllers;

use App\Models\Process;
use Illuminate\Http\Request;

// php artisan make:controller ProcessController --api --model=Process
class ProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $processes = Process::all();
        return response()->json($processes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $process = Process::create($validated);

        return response()->json($process, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Process $process)
    {
        return response()->json($process);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Process $process)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);

        $process->update($validated);

        return response()->json($process);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Process $process)
    {
        $process->delete();
        return response()->json(null, 204);
    }
}
