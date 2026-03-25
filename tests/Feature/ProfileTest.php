<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        // 1. Arrange : Création d'un utilisateur
        $user = User::factory()->create();

        // 2. Act : Accès à la page de profil
        $response = $this
            ->actingAs($user)
            ->get(route('profile.edit', $user->id));

        // 3. Assert : Vérification de l'affichage
        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        // 1. Arrange
        $user = User::factory()->create();

        // 2. Act : Mise à jour des informations
        $response = $this
            ->actingAs($user)
            ->patch(route('profile.update'), [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        // 3. Assert
        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('profile.edit', $user->id)); // Redirection potentielle vers l'édition

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        // 1. Arrange
        $user = User::factory()->create();

        // 2. Act
        $response = $this
            ->actingAs($user)
            ->patch(route('profile.update'), [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        // 3. Assert
        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('profile.edit', $user->id));

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        // 1. Arrange
        $user = User::factory()->create();

        // 2. Act : Suppression du compte
        $response = $this
            ->actingAs($user)
            ->delete(route('profile.destroy', $user->id), [
                'password' => 'password',
            ]);

        // 3. Assert
        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        // 1. Arrange
        $user = User::factory()->create();

        // 2. Act
        $response = $this
            ->actingAs($user)
            ->from(route('profile.edit', $user->id))
            ->delete(route('profile.destroy', $user->id), [
                'password' => 'wrong-password',
            ]);

        // 3. Assert
        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect(route('profile.edit', $user->id));

        $this->assertNotNull($user->fresh());
    }
}
