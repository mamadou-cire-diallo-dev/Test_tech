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
        // Autorise uniquement l'employé propriétaire à soumettre une dépense
        // si son statut est DRAFT.

        $this->authorize('submit', $expense);

        $expense->update(['status' => 'SUBMITTED']);

        return response()->json([
            "message"=>"Dépenses soumises pour validation",
            "expense"=>$expense
        ]);

    }

    public function approve(Expense $expense)
    {
        // Autorise uniquement un manager à approuver une dépense.
        // Le statut de la dépense doit être SUBMITTED (géré par la policy).

        $this->authorize('manage', Expense::class);

        $expense->update(['status' => 'APPROVED']);

        return response()->json([
            "message" => "Dépense approuvée",
            "expense" => $expense
        ]);
    }

    public function reject(Request $request, Expense $expense)
    {
        // Autorise uniquement un manager à rejeter une dépense.
        // Le statut de la dépense doit être SUBMITTED (géré par la policy).

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
        // Autorise uniquement un manager à marquer une dépense comme payée.
        // Le statut de la dépense doit être APPROVED (géré par la policy).

        $this->authorize('manage', Expense::class);

        $expense->update(['status' => 'PAID']);

        return response()->json([
            "message" => "Dépense marquée comme payée",
            "expense" => $expense
        ]);
    }
}
