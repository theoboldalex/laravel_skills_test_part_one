<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserSignupTest extends TestCase
{
    use RefreshDatabase;

    private const VALID_REQUEST_BODY = [
        'name' => 'Capt. Birds Eye',
        'password' => 'Hunter2024',
        'email' => 'fish_fingers69@example.org',
        'created' => '2024-10-01 00:00:00',
        'role' => 'admin',
    ];

    #[DataProvider('validationCaseProvider')]
    public function test_the_signup_endpoint_validates_requests(
        array $body,
        int $responseCode,
        array $responseBody
    ): void {
        $response = $this->postJson(route('user.signup'), $body);

        $response->assertStatus($responseCode)
            ->assertJson($responseBody);
    }

    public static function validationCaseProvider(): array
    {
        return [
            'name must not be empty' => [
                ['name' => ''],
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [],
            ],
            'password must not be empty' => [
                [
                    'name' => 'Capt. Birds Eye',
                    'password' => '',
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [],
            ],
            'password must contain at least one digit (0-9)' => [
                [
                    'name' => 'Capt. Birds Eye',
                    'password' => 'HunterHunter',
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [],
            ],
            'password must contain at least one lower case character' => [
                [
                    'name' => 'Capt. Birds Eye',
                    'password' => 'HUNTER2',
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [],
            ],
            'password must contain at least one upper case character' => [
                [
                    'name' => 'Capt. Birds Eye',
                    'password' => 'hunter2024',
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [],
            ],
            'password must contain at least 8 characters' => [
                [
                    'name' => 'Capt. Birds Eye',
                    'password' => 'Hunter2024',
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [],
            ],
            'password must contain at most 64 characters' => [
                [
                    'name' => 'Capt. Birds Eye',
                    'password' => 'Hunter2Hunter2Hunter2Hunter2Hunter2Hunter2Hunter2Hunter2Hunter2Hunter2Hunter2Hunter2Hunter2Hunter2Hunter2Hunter2',
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [],
            ],
            'email must not be empty' => [
                [
                    'name' => 'Capt. Birds Eye',
                    'password' => 'Hunter2024',
                    'email' => '',
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [],
            ],
            'email must be a valid email address' => [
                [
                    'name' => 'Capt. Birds Eye',
                    'password' => 'Hunter2024',
                    'email' => 'testexample.org',
                    'created' => '2024-10-01 00:00:00',
                    'role' => 'admin',
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [],
            ],
            'created date must not be empty' => [
                [
                    'name' => 'Capt. Birds Eye',
                    'password' => 'Hunter2024',
                    'email' => 'fish_fingers69@example.org',
                    'created' => '',
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [],
            ],
            'created date must be a valid timestamp (Y-m-d H:i:s)' => [
                [
                    'name' => 'Capt. Birds Eye',
                    'password' => 'Hunter2024',
                    'email' => 'fish_fingers69@example.org',
                    'created' => '25/12/2023',
                    'role' => 'admin',
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [],
            ],
            'user role must not be empty' => [
                [
                    'name' => 'Capt. Birds Eye',
                    'password' => 'Hunter2024',
                    'email' => 'fish_fingers69@example.org',
                    'created' => '2024-10-01 00:00:00',
                    'role' => '',
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [],
            ],
            'user role must be a valid role (admin, user)' => [
                [
                    'name' => 'Capt. Birds Eye',
                    'password' => 'Hunter2024',
                    'email' => 'fish_fingers69@example.org',
                    'created' => '2024-10-01 00:00:00',
                    'role' => 'not_a_valid_role',
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [],
            ],
            'a valid request' => [
                self::VALID_REQUEST_BODY,
                Response::HTTP_CREATED,
                [
                    'name' => 'Capt. Birds Eye',
                    'email' => 'fish_fingers69@example.org',
                    'created' => '2024-10-01 00:00:00',
                    'role' => 'admin',
                    'id' => 1,
                ],
            ],
        ];
    }

    public function test_errors_are_handled_if_a_user_cannot_be_created(): void
    {
        $userMock = $this->mock(User::class, function ($mock) {
            $mock->shouldReceive('create')
                ->once()
                ->andThrow(new \Exception('Database error'));
        });

        Log::shouldReceive('error')
            ->once()
            ->with('Database error');

        $this->app->instance(User::class, $userMock);

        $response = $this->postJson(route('user.signup'), self::VALID_REQUEST_BODY);

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->assertJson([
                'success' => false,
                'message' => 'Unable to create user',
            ]);
    }
}
