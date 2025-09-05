<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Document;
use App\Models\CreditTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get dashboard statistics
        $stats = [
            'total_users' => User::count(),
            'total_documents' => Document::count(),
            'total_credits_issued' => CreditTransaction::where('type', 'purchase')->sum('credits'),
            'total_credits_used' => abs(CreditTransaction::where('type', 'usage')->sum('credits')),
            'new_users_today' => User::whereDate('created_at', today())->count(),
            'new_users_this_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)->count(),
            'documents_processed_today' => Document::whereDate('created_at', today())->count(),
            'documents_processed_this_week' => Document::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'documents_processed_this_month' => Document::whereMonth('created_at', now()->month)->count(),
        ];

        // Get recent users
        $recentUsers = User::latest()->take(10)->get();

        // Get recent documents
        $recentDocuments = Document::with('user')->latest()->take(10)->get();

        // Get user registration chart data (last 7 days)
        $userChartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $userCount = User::whereDate('created_at', $date)->count();
            
            $userChartData[] = [
                'date' => $date->format('M j'),
                'users' => (int) $userCount
            ];
        }
        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentDocuments', 'userChartData'));
    }
}