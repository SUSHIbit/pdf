<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $documents = Document::where('user_id', $user->id)
            ->with('questionSet')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard.index', compact('documents'));
    }
}