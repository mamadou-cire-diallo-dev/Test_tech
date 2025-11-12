<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Added this line

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/expenses', function () {
        $user = Auth::user();
        if ($user->role === 'EMPLOYEE') {
            $expenses = $user->expenses()->latest()->get();
        } else {
            $expenses = \App\Models\Expense::latest()->get();

        }

//        dd(back()->getTargetUrl());
        return view('expenses.index', compact('expenses'));
    })->name('expenses.index');

    Route::get('/expenses/create', function () {
        return view('expenses.create');
    })->name('expenses.create');

    Route::post('/expenses', function () {

        return redirect()->route('expenses.index')->with('success', 'Dépense créée avec succès (placeholder).');
    })->name('expenses.store');

    Route::get('/exports', function () {
        return view('exports.index');
    })->name('exports.index');

});

require __DIR__.'/auth.php';
