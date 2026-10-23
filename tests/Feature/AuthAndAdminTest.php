<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_documents(): void
    {
        $response = $this->get(route('admin.documents.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_admin_documents(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.documents.index'));

        $response->assertOk();
    }

    public function test_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@test.unal.edu.co',
            'password' => bcrypt('secret-test'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'admin@test.unal.edu.co',
            'password' => 'secret-test',
        ]);

        $response->assertRedirect(route('admin.documents.index'));
        $this->assertAuthenticatedAs($user);
    }
}
