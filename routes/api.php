<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\ExpenseActionController;
use App\Http\Controllers\api\ExpenseController;
use Illuminate\Support\Facades\Route;


Route::get('/',function(){
    return response()->json(["message"=>"API test Technique"]);
});

Route::post('/login',[AuthController::class,'login'])->name('login');


Route::middleware('auth:sanctum')->group(function(){
    Route::delete('/logout',[AuthController::class,'logout'])->name('logout');


    Route::name('expenses.')->prefix('expenses') ->controller(ExpenseController::class)->group(function(){
        Route::get('/','index')->name('index');
        Route::post('/','store')->name('store');
        Route::put('/{expense}','update')->name('update');


    });


    Route::post('/expenses/{expense}/submit', [ExpenseActionController::class, 'submit'])->name('expenses.submit');
    Route::post('/expenses/{expense}/approve', [ExpenseActionController::class, 'approve'])->name('expenses.approve');
    Route::post('/expenses/{expense}/reject', [ExpenseActionController::class, 'reject'])->name('expenses.reject');
    Route::post('/expenses/{expense}/pay', [ExpenseActionController::class, 'pay'])->name('expenses.pay');

    Route::get('/stats/summary', [\App\Http\Controllers\api\StatsController::class, 'summary'])->name('stats.summary');

    Route::post('/exports/expenses', [\App\Http\Controllers\api\ExportController::class, 'store'])->name('exports.store');
    Route::get('/exports/{export}', [\App\Http\Controllers\api\ExportController::class, 'show'])->name('exports.show');
});