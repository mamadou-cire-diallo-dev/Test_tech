<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();


    }

    /**
     * Test an employee can create an expense.
     *
     * @return void
     */
    public function test_employee_can_create_an_expense()
    {
        $employee = User::factory()->create(['role' => 'EMPLOYEE']);
        $token = $employee->createToken('test-token')->plainTextToken;

        $expenseData = [
            'title' => 'Test Expense',
            'amount' => 100.50,
            'currency' => 'EUR',
            'spent_at' => '2025-11-10',
            'category' => 'TRAVEL',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/expenses', $expenseData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'Test Expense', 'status' => 'DRAFT']);

        $this->assertDatabaseHas('expenses', [
            'user_id' => $employee->id,
            'title' => 'Test Expense',
            'status' => 'DRAFT',
        ]);
    }

    /**
     * Test a manager can approve a submitted expense.
     *
     * @return void
     */
    public function test_manager_can_approve_a_submitted_expense()
    {
        $manager = User::factory()->create(['role' => 'MANAGER']);
        $employee = User::factory()->create(['role' => 'EMPLOYEE']);
        $expense = Expense::factory()->create([
            'user_id' => $employee->id,
            'status' => 'SUBMITTED',
        ]);
        $token = $manager->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson("/api/expenses/{$expense->id}/approve");

        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => 'APPROVED']);

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'status' => 'APPROVED',
        ]);

        $this->assertDatabaseHas('expense_logs', [
            'expense_id' => $expense->id,
            'user_id' => $manager->id,
            'from_status' => 'SUBMITTED',
            'to_status' => 'APPROVED',
        ]);
    }

    /**
     * Test a manager can reject a submitted expense with a comment.
     *
     * @return void
     */
    public function test_manager_can_reject_a_submitted_expense_with_comment()
    {
        $manager = User::factory()->create(['role' => 'MANAGER']);
        $employee = User::factory()->create(['role' => 'EMPLOYEE']);
        $expense = Expense::factory()->create([
            'user_id' => $employee->id,
            'status' => 'SUBMITTED',
        ]);
        $token = $manager->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson("/api/expenses/{$expense->id}/reject", ['reason' => 'Reason for rejection']);

        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => 'REJECTED']);

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'status' => 'REJECTED',
        ]);

        $this->assertDatabaseHas('expense_logs', [
            'expense_id' => $expense->id,
            'user_id' => $manager->id,
            'from_status' => 'SUBMITTED',
            'to_status' => 'REJECTED',
        ]);
    }
}
