<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserDetailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_user_by_id(): void
    {
        $userDetails = [
            'name' => 'Capt. Birds Eye',
            'email' => 'fish_fingers69@example.com',
            'created' => '2024-10-01 00:00:00',
            'role' => 'admin'
        ];
        $user = User::factory()->create($userDetails);
        $response = $this->get(route('user', $user->id));
        
        $response->assertStatus(200)
            ->assertJson(array_merge($userDetails, ['id' => $user->id]));
    }
    
    public function test_requesting_an_invalid_user_returns_404_not_found(): void
    {
        $response = $this->get(route('user', 69420));
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}