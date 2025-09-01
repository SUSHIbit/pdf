<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Folder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get folders - simple query
        $folders = Folder::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get documents not in any folder
        $documents = Document::where('user_id', $user->id)
            ->whereNull('folder_id')
            ->with('questionSet')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get all folders for the move document dropdown
        $allFolders = Folder::where('user_id', $user->id)
            ->orderBy('name')
            ->get();

        return view('dashboard.index', compact('documents', 'folders', 'allFolders'));
    }
}