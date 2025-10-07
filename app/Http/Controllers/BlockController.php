<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BlockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blocks = Block::with('level')->get();
        return response()->json($blocks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'level_id' => 'required|exists:levels,id',
            'name' => 'required|string|max:255',
            'parent_block_id' => 'nullable|exists:blocks,id',
            'has_text' => 'required|boolean',
        ]);


        if ($validated['parent_block_id'] == null) {
            // If no parent block is specified, generate a unique code
            $level = Level::find($validated['level_id']);
            $children_count = Block::where('level_id', $validated['level_id'])->count();
            $validated['code'] = str_pad($children_count + 1, 2, '0', STR_PAD_LEFT);
        } else {
            // Generate a code based on the parent block
            $parentBlock = Block::find($validated['parent_block_id']);
            $children_count = Block::where('parent_block_id', $parentBlock->id)->count();
            $parentCode = $parentBlock->code;
            $validated['code'] = $parentCode . str_pad($children_count + 1, 2, '0', STR_PAD_LEFT);
        }

        $block = Block::create($validated);

        return response()->json($block, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Block $block)
    {
        $block->load('level', 'parentBlock');
        return response()->json($block);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Block $block)
    {
        $validated = $request->validate([
            //'level_id' => 'sometimes|required|exists:levels,id',
            'name' => 'sometimes|required|string|max:255',
            'has_text' => 'sometimes|required|boolean',
            //'parent_block_id' => 'nullable|exists:blocks,id',
        ]);

        $block->update($validated);

        return response()->json($block);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Block $block)
    {
        $block->delete();
        return response()->json(null, 204);
    }
}
