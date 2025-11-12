<?php

namespace App\Observers;

use App\Models\Expense;
use App\Models\ExpenseLog;
use Illuminate\Support\Facades\Auth;

class ExpenseObserver
{
    /**
     * Handle the Expense "created" event.
     */
    public function created(Expense $expense): void
    {
        //
    }

    /**
     * Handle the Expense "updating" event.
     */
    public function updating(Expense $expense): void
    {
        if ($expense->isDirty('status')) {
            ExpenseLog::create([
                'expense_id' => $expense->id,
                'user_id' => Auth::id(), // Assuming the user making the change is authenticated
                'from_status' => $expense->getOriginal('status'),
                'to_status' => $expense->status,
            ]);
        }
    }

    /**
     * Handle the Expense "deleted" event.
     */
    public function deleted(Expense $expense): void
    {
        //
    }

    /**
     * Handle the Expense "restored" event.
     */
    public function restored(Expense $expense): void
    {
        //
    }

    /**
     * Handle the Expense "force deleted" event.
     */
    public function forceDeleted(Expense $expense): void
    {
        //
    }
}
