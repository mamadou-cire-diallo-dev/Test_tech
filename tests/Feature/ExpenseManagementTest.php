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
     * Test qu'un employé peut créer une note de frais.
     *
     * @return void
     */
    public function test_un_employe_peut_creer_une_note_de_frais()
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
     * Test qu'un manager peut approuver une note de frais soumise.
     *
     * @return void
     */
    public function test_un_manager_peut_approuver_une_note_de_frais_soumise()
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
     * Test qu'un manager peut rejeter une note de frais soumise avec un commentaire.
     *
     * @return void
     */
    public function test_un_manager_peut_rejeter_une_note_de_frais_soumise_avec_un_commentaire()
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
