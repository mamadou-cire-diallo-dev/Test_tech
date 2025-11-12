<?php

namespace App\Jobs;

use App\Models\Expense;
use App\Models\Export;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ExportExpensesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The export instance.
     *
     * @var \App\Models\Export
     */
    public $export;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Export $export
     * @return void
     */
    public function __construct(Export $export)
    {
        $this->export = $export;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $period = $this->export->meta['period'];
            $status = $this->export->meta['status'];

            $expenses = Expense::where('status', $status)
                ->whereYear('spent_at', '=', substr($period, 0, 4))
                ->whereMonth('spent_at', '=', substr($period, 5, 2))
                ->get();

            $fileName = 'exports/expenses-' . $this->export->id . '-' . time() . '.csv';
            $filePath = storage_path('app/public/' . $fileName);

            //
            Storage::disk('public')->makeDirectory('exports');

            $file = fopen($filePath, 'w');

            //  CSV headers
            fputcsv($file, ['ID', 'Title', 'Amount', 'Currency', 'Spent At', 'Category', 'Status']);

            // CSV data
            foreach ($expenses as $expense) {
                fputcsv($file, [
                    $expense->id,
                    $expense->title,
                    $expense->amount,
                    $expense->currency,
                    $expense->spent_at,
                    $expense->category,
                    $expense->status,
                ]);
            }

            fclose($file);



            $this->export->update([
                'status' => 'READY',
                'file_path' => $fileName
            ]);


        } catch (Throwable $e) {
            $this->export->update(['status' => 'FAILED']);

        }
    }
}
