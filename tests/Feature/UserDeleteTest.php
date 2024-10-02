<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserDeleteTest extends TestCase
{
    use RefreshDatabase;

    private const USER_ID = 69;

    public function test_delete_a_user(): void
    {
        $user = User::factory()->create([
            'id' => self::USER_ID,
            'created' => '2024-10-01 00:00:00',
        ]);

        $response = $this->delete(route('user.delete', $user->id));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function test_unable_to_delete_user_because_it_does_not_exist(): void
    {
        $userMock = $this->mock(User::class, function ($mock) {
            $mock->shouldReceive('resolveRouteBinding')
                ->once();
        });

        $this->app->instance(User::class, $userMock);

        $response = $this->delete(route('user.delete', self::USER_ID));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_unable_to_delete_user_because_an_error_occurred_on_the_server(): void
    {
        $userMock = $this->mock(User::class, function ($mock) {
            $mock->shouldReceive('resolveRouteBinding')
                ->once()
                ->andReturn(new User([
                    'id' => self::USER_ID,
                    'created' => '2024-10-01 00:00:00',
                ]));

            $mock->shouldReceive('delete')
                ->once()
                ->andThrow(new \Exception('Database error'));
        });

        Log::shouldReceive('error')
            ->once()
            ->with('Database error');

        $this->app->instance(User::class, $userMock);

        $response = $this->delete(route('user.delete', self::USER_ID));

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function test_unable_to_delete_a_user_because_the_account_is_older_than_14_days(): void
    {
        $user = User::factory()->create([
            'id' => self::USER_ID,
            'created' => '2023-05-01 00:00:00',
        ]);

        $response = $this->delete(route('user.delete', self::USER_ID));

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
