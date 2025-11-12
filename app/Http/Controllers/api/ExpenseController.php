<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ExpenseController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Expense::class);
        //
        $query = Expense::query();

        // Filtres

        // status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Period
        if ($request->filled('period')) {
                            $year = substr($request->period, 0, 4);
                            $month = substr($request->period, 5, 2);

                            $query
                                ->whereYear('expenses.spent_at', $year)
                                ->whereMonth('expenses.spent_at', $month);
        }

        /**
         * @var $user User
         */

        $user = Auth::user();

        if ($user->role==='EMPLOYEE') {
            $query->where('user_id', $user->id);
        }



        return response()->json([$query->get()]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request)
    {
        $this->authorize('create', Expense::class);



        $expense = Expense::create([
          'user_id' => Auth::id(),
          'title' => $request->title,
          'amount'=> $request->amount,
          'category' => $request->category,
          'currency' => 'EUR',
          'spent_at' => $request->spent_at,
          'status' => 'DRAFT',
        ]) ;


        return response()->json([
            'message'=>'Dépenses créer avec succès',
            'expense'=>$expense
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        $this->authorize('update', $expense);


        $expense->update($request->validated());
        return response()->json([
            "message"=>"Dépenses mise à jour ",
            "expense"=>$expense
        ]);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


}
