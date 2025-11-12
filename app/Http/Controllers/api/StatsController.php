<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
            public function summary(Request $request)
            {
                $this->authorize('manage', Expense::class);
        
                $validated = $request->validate([
                    'period' => 'sometimes|date_format:Y-m',
                ]);
        
                $period = $validated['period'] ?? null;
                $cacheKey = 'stats.summary.' . ($period ?? 'all');
        
                $stats = Cache::remember($cacheKey, now()->addSeconds(60), function () use ($period) {
                    $query = Expense::query();
        
                    if ($period) {
                        $query->whereYear('spent_at', '=', substr($period, 0, 4))
                            ->whereMonth('spent_at', '=', substr($period, 5, 2));
                    }
        
                    $totalExpenses = $query->clone()->count();
                    $totalAmount = $query->clone()->sum('amount');
        
                    $expensesByStatus = $query->clone()->select('status', DB::raw('count(*) as count'))
                        ->groupBy('status')
                        ->get()
                        ->pluck('count', 'status');
        
                    return [
                        'period' => $period ?? '',
                        'total_expenses' => $totalExpenses,
                        'total_amount' => $totalAmount,
                        'expenses_by_status' => $expensesByStatus,
                    ];
                });
        
                return response()->json($stats);
            }}
