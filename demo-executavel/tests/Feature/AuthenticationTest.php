<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DemoScenarioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DemoScenarioSeeder::class);
    }

    public function test_master_can_authenticate_with_demo_credentials(): void
    {
        $response = $this->post(route('login.store'), [
            'login' => config('demo.master.email'),
            'password' => config('demo.master.password'),
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs(User::query()->sole());
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        $response = $this->from(route('login'))->post(route('login.store'), [
            'login' => config('demo.master.email'),
            'password' => 'senha-incorreta',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_end_session_even_without_master_access(): void
    {
        $user = User::factory()->create([
            'role' => 'viewer',
            'active' => false,
        ]);

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
