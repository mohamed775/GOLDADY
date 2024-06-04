<?php

namespace Tests\Feature;

use App\Http\Controllers\api\authController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function testRegister()
    {
        $request = new Request([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $controller = new authController();

        $response = $controller->register($request);

        $this->assertEquals(201, $response->getStatusCode());

        // Ensure user is created in the database
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    public function testLogin()
    {
        // Create a user for testing
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $request = new Request([
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $controller = new AuthController();

        $response = $controller->login($request);

        $this->assertEquals(200, $response->getStatusCode());

        // Ensure access token is returned
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('access_token', $responseData);

        // Ensure access token is valid
        $this->assertNotNull(Auth::user());
    }

    public function testLogout()
    {
        // Authenticate a user
        $user = User::factory()->create();
        Auth::login($user);

        $controller = new AuthController();

        $response = $controller->logout();

        $this->assertEquals(200, $response->getStatusCode());

        // Ensure user is logged out
        $this->assertGuest();
    }
}
