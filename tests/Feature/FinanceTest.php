<?php

namespace Tests\Feature;

use App\Models\SavingSetting;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinanceTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(): User
    {
        $user = User::factory()->create();

        $user->savingSetting()->create([
            'target_amount' => SavingSetting::DEFAULT_TARGET,
            'daily_amount' => SavingSetting::DEFAULT_DAILY,
        ]);

        return $user;
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_register_creates_account_with_default_saving_targets(): void
    {
        $response = $this->post('/register', [
            'name' => 'Dinda Ayu',
            'email' => 'dinda@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();

        $user = User::whereEmail('dinda@example.com')->firstOrFail();
        $this->assertSame(1000000.0, (float) $user->savingSetting->target_amount);
        $this->assertSame(5000.0, (float) $user->savingSetting->daily_amount);
    }

    public function test_login_rejects_wrong_password_with_indonesian_message(): void
    {
        $user = User::factory()->create(['password' => 'password123']);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'salah-salah',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->assertStringContainsString(
            'tidak sesuai',
            session('errors')->first('email')
        );
    }

    public function test_totals_are_calculated_automatically_on_dashboard(): void
    {
        $user = $this->makeUser();

        $user->transactions()->create([
            'type' => Transaction::TYPE_INCOME,
            'category' => Transaction::CATEGORY_ALLOWANCE,
            'description' => 'Uang saku mingguan',
            'amount' => 500000,
            'date' => now()->toDateString(),
        ]);

        $user->transactions()->create([
            'type' => Transaction::TYPE_EXPENSE,
            'category' => 'Makanan',
            'description' => 'Makan siang',
            'amount' => 25000,
            'date' => now()->toDateString(),
        ]);

        $user->transactions()->create([
            'type' => Transaction::TYPE_EXPENSE,
            'category' => 'Transportasi',
            'description' => 'Ojek kampus',
            'amount' => 15000,
            'date' => now()->subDay()->toDateString(),
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Rp500.000'); // uang saku + total pemasukan
        $response->assertSee('Rp40.000');  // total pengeluaran
        $response->assertSee('Rp460.000'); // saldo tersisa
        $response->assertSee('Rp0');       // belum ada tabungan
    }

    public function test_expense_category_cannot_be_used_as_income(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->post('/transactions', [
            'type' => Transaction::TYPE_INCOME,
            'category' => 'Makanan',
            'amount' => 10000,
            'date' => now()->toDateString(),
        ]);

        $response->assertSessionHasErrors('category');
        $this->assertDatabaseCount('transactions', 0);
    }

    public function test_transaction_search_is_scoped_to_keyword(): void
    {
        $user = $this->makeUser();

        $user->transactions()->create([
            'type' => Transaction::TYPE_EXPENSE,
            'category' => 'Makanan',
            'description' => 'Makan siang kantin',
            'amount' => 25000,
            'date' => now()->toDateString(),
        ]);

        $user->transactions()->create([
            'type' => Transaction::TYPE_EXPENSE,
            'category' => 'Transportasi',
            'description' => 'Ojek ke kampus',
            'amount' => 18000,
            'date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->get('/transactions?q=ojek');

        $response->assertOk();
        $response->assertSee('Ojek ke kampus');
        $response->assertDontSee('Makan siang kantin');
    }

    public function test_savings_progress_and_daily_reminder(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)
            ->post('/savings/today', ['amount' => 5000])
            ->assertRedirect();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Menabung hari ini sudah dicatat');
        $response->assertDontSee('Jangan lupa menabung');
        $response->assertSee('Rp5.000');
    }

    public function test_saving_targets_can_be_updated(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->put('/savings/settings', [
            'target_amount' => 2000000,
            'daily_amount' => 10000,
        ])->assertRedirect('/savings/settings');

        $this->assertSame(2000000.0, (float) $user->fresh()->savingSetting->target_amount);
        $this->assertSame(10000.0, (float) $user->fresh()->savingSetting->daily_amount);
    }

    public function test_users_cannot_read_or_modify_each_others_records(): void
    {
        $owner = $this->makeUser();
        $stranger = $this->makeUser();

        $transaction = $owner->transactions()->create([
            'type' => Transaction::TYPE_INCOME,
            'category' => Transaction::CATEGORY_ALLOWANCE,
            'amount' => 500000,
            'date' => now()->toDateString(),
        ]);

        $saving = $owner->savings()->create([
            'amount' => 5000,
            'date' => now()->toDateString(),
        ]);

        // The stranger sees an empty ledger, not the owner's data.
        $this->actingAs($stranger)->get('/transactions')
            ->assertOk()
            ->assertDontSee('Rp500.000');

        // Every read and write against the owner's records is refused.
        $this->actingAs($stranger)->get("/transactions/{$transaction->id}")->assertNotFound();
        $this->actingAs($stranger)->get("/transactions/{$transaction->id}/edit")->assertNotFound();
        $this->actingAs($stranger)->get("/savings/{$saving->id}")->assertNotFound();
        $this->actingAs($stranger)->get("/savings/{$saving->id}/edit")->assertNotFound();

        $this->actingAs($stranger)->put("/transactions/{$transaction->id}", [
            'type' => Transaction::TYPE_INCOME,
            'category' => Transaction::CATEGORY_ALLOWANCE,
            'amount' => 1,
            'date' => now()->toDateString(),
        ])->assertNotFound();

        $this->actingAs($stranger)
            ->delete("/transactions/{$transaction->id}")
            ->assertNotFound();

        // The owner's data is untouched.
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'amount' => 500000,
        ]);
        $this->assertDatabaseHas('savings', ['id' => $saving->id]);
    }

    public function test_logout_invalidates_the_session(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->post('/logout')->assertRedirect('/login');

        $this->assertGuest();
    }
}
