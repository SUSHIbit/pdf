<?php
// app/Http/Controllers/FolderController.php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FolderController extends Controller
{
    public function index()
    {
        $folders = Folder::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('folders.index', compact('folders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $folder = Folder::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Folder created successfully.');
    }

    public function show(Folder $folder)
    {
        if ($folder->user_id !== auth()->id()) {
            abort(403);
        }

        $documents = Document::where('folder_id', $folder->id)
            ->with('questionSet')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('folders.show', compact('folder', 'documents'));
    }

    public function edit(Folder $folder)
    {
        if ($folder->user_id !== auth()->id()) {
            abort(403);
        }

        return view('folders.edit', compact('folder'));
    }

    public function update(Request $request, Folder $folder)
    {
        if ($folder->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $folder->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('folders.show', $folder)
            ->with('success', 'Folder updated successfully.');
    }

    public function destroy(Folder $folder)
    {
        if ($folder->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            // Move all documents back to no folder
            Document::where('folder_id', $folder->id)->update(['folder_id' => null]);
            
            // Delete the folder
            $folder->delete();

            return redirect()
                ->route('dashboard')
                ->with('success', 'Folder deleted successfully. Documents moved back to Recent Documents.');

        } catch (\Exception $e) {
            Log::error('Folder deletion failed: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Failed to delete folder. Please try again.');
        }
    }

    public function moveDocument(Request $request)
    {
        $request->validate([
            'document_id' => 'required|integer|exists:documents,id',
            'folder_id' => 'nullable|integer|exists:folders,id',
        ]);

        $document = Document::findOrFail($request->document_id);
        
        if ($document->user_id !== auth()->id()) {
            abort(403);
        }

        // Verify folder belongs to user if moving to a folder
        if ($request->folder_id) {
            $folder = Folder::findOrFail($request->folder_id);
            if ($folder->user_id !== auth()->id()) {
                abort(403);
            }
        }

        $document->update(['folder_id' => $request->folder_id]);

        $message = $request->folder_id 
            ? 'Document moved to folder successfully.'
            : 'Document moved to Recent Documents successfully.';

        return redirect()
            ->back()
            ->with('success', $message);
    }
}