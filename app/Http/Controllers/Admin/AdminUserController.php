<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CreditTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by credits
        if ($request->has('credits_filter') && $request->credits_filter !== '') {
            $creditsFilter = $request->credits_filter;
            if ($creditsFilter === 'zero') {
                $query->where('credits', 0);
            } elseif ($creditsFilter === 'low') {
                $query->whereBetween('credits', [1, 5]);
            } elseif ($creditsFilter === 'medium') {
                $query->whereBetween('credits', [6, 20]);
            } elseif ($creditsFilter === 'high') {
                $query->where('credits', '>', 20);
            }
        }

        // Sort functionality
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        if (in_array($sortBy, ['name', 'email', 'credits', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $users = $query->withCount('documents')->paginate(20);
        
        // Manually append query parameters to pagination links
        $users->appends([
            'search' => $request->get('search'),
            'credits_filter' => $request->get('credits_filter'),
            'sort' => $request->get('sort'),
            'order' => $request->get('order'),
        ]);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('creditTransactions', 'documents.questionSet');
        
        $stats = [
            'total_documents' => $user->documents()->count(),
            'completed_documents' => $user->documents()->where('status', 'completed')->count(),
            'total_credits_purchased' => $user->creditTransactions()->where('type', 'purchase')->sum('credits'),
            'total_credits_used' => abs($user->creditTransactions()->where('type', 'usage')->sum('credits')),
            'total_amount_spent' => $user->creditTransactions()->where('type', 'purchase')->sum('amount'),
        ];

        $recentTransactions = $user->creditTransactions()
            ->latest()
            ->take(10)
            ->get();

        $recentDocuments = $user->documents()
            ->latest()
            ->take(10)
            ->get();

        return view('admin.users.show', compact('user', 'stats', 'recentTransactions', 'recentDocuments'));
    }

    public function addCredits(Request $request, User $user)
    {
        $request->validate([
            'credits' => 'required|integer|min:1|max:1000',
            'reason' => 'required|string|max:255',
        ]);

        $credits = $request->credits;
        $reason = $request->reason;

        try {
            DB::transaction(function () use ($user, $credits, $reason) {
                // Add credits to user
                $user->increment('credits', $credits);

                // Log the transaction
                CreditTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'purchase',
                    'credits' => $credits,
                    'amount' => null,
                    'description' => "Admin added credits: {$reason}",
                ]);

                Log::info('Admin added credits to user', [
                    'admin_id' => auth('admin')->id(),
                    'user_id' => $user->id,
                    'credits_added' => $credits,
                    'reason' => $reason
                ]);
            });

            return redirect()
                ->route('admin.users.show', $user)
                ->with('success', "Successfully added {$credits} credits to {$user->name}'s account.");

        } catch (\Exception $e) {
            Log::error('Failed to add credits to user', [
                'admin_id' => auth('admin')->id(),
                'user_id' => $user->id,
                'credits' => $credits,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to add credits. Please try again.');
        }
    }

    public function updateCredits(Request $request, User $user)
    {
        $request->validate([
            'credits' => 'required|integer|min:0|max:1000',
            'reason' => 'required|string|max:255',
        ]);

        $newCredits = $request->credits;
        $reason = $request->reason;
        $oldCredits = $user->credits;
        $difference = $newCredits - $oldCredits;

        try {
            DB::transaction(function () use ($user, $newCredits, $oldCredits, $difference, $reason) {
                // Update user credits
                $user->update(['credits' => $newCredits]);

                // Log the transaction if there's a difference
                if ($difference !== 0) {
                    $type = $difference > 0 ? 'purchase' : 'usage';
                    $description = $difference > 0 
                        ? "Admin increased credits from {$oldCredits} to {$newCredits}: {$reason}"
                        : "Admin decreased credits from {$oldCredits} to {$newCredits}: {$reason}";

                    CreditTransaction::create([
                        'user_id' => $user->id,
                        'type' => $type,
                        'credits' => $difference,
                        'amount' => null,
                        'description' => $description,
                    ]);
                }

                Log::info('Admin updated user credits', [
                    'admin_id' => auth('admin')->id(),
                    'user_id' => $user->id,
                    'old_credits' => $oldCredits,
                    'new_credits' => $newCredits,
                    'difference' => $difference,
                    'reason' => $reason
                ]);
            });

            return redirect()
                ->route('admin.users.show', $user)
                ->with('success', "Successfully updated {$user->name}'s credits to {$newCredits}.");

        } catch (\Exception $e) {
            Log::error('Failed to update user credits', [
                'admin_id' => auth('admin')->id(),
                'user_id' => $user->id,
                'new_credits' => $newCredits,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to update credits. Please try again.');
        }
    }
}