<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ExpenseActionController extends Controller
{
    use AuthorizesRequests;

    public  function submit(Expense $expense)
    {
        $this->authorize('submit', $expense);

        $expense->update(['status' => 'SUBMITTED']);

        return response()->json([
            "message"=>"Dépenses soumises pour validation",
            "expense"=>$expense
        ]);

    }

    public function approve(Expense $expense)
    {
        $this->authorize('manage', Expense::class);

        $expense->update(['status' => 'APPROVED']);

        return response()->json([
            "message" => "Dépense approuvée",
            "expense" => $expense
        ]);
    }

    public function reject(Request $request, Expense $expense)
    {
        $this->authorize('manage', Expense::class);

        // Motif
        $request->validate(['reason' => 'required|string|max:255']);

        $expense->update(['status' => 'REJECTED']);



        return response()->json([
            "message" => "Dépense rejetée avec le motif : " . $request->reason,
            "expense" => $expense
        ]);
    }

    public function pay(Expense $expense)
    {
        $this->authorize('manage', Expense::class);

        $expense->update(['status' => 'PAID']);

        return response()->json([
            "message" => "Dépense marquée comme payée",
            "expense" => $expense
        ]);
    }
}
