<?php

namespace Tests\Integration\Controller;

use Tests\IntegrationTestCase;

class AuthenticateControllerTest extends IntegrationTestCase
{
    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::authenticate
     */
    public function it_throws_validation_exception_for_invalid_request()
    {
        $user = $this->post('/user/authenticate', [
            'email' => 'superadmin@admin.com',
        ]);

        $response = json_decode($user->response->getContent(), true);

        $this->assertResponseStatus(422);
        $this->assertEquals([
            'errors' => [
                'password' => [
                    'The password field is required.',
                ],
            ],
        ], $response);
    }
    /**
     * @test
     *
     * @covers ::authenticate
     */
    public function it_fails_authentication_for_invalid_user()
    {
        $this->post('/user/authenticate', [
            'email' => 'invalid@invalid.com',
            'password' => 'invalid',
        ]);

        $this->assertResponseStatus(404);
    }

    /**
     * @test
     */
    public function it_fails_authentication_for_invalid_email()
    {
        $user = $this->post('/user/authenticate', [
            'email' => 'superadmin@admin.com',
            'password' => 'invalid',
        ]);

        $this->assertResponseStatus(400);

        $response = json_decode($user->response->getContent(), true);

        $this->assertEquals([
            'errors' => [
                'auth' => [
                    'Wrong credentials.',
                ],
            ],
        ], $response);
    }


    /**
     * @test
     *
     * @covers ::authenticate
     */
    public function it_authenticates_user()
    {
        $user = $this->post('/user/authenticate', [
            'email' => 'superadmin@admin.com',
            'password' => 'password@123',
        ]);

        $response = json_decode($user->response->getContent(), true);

        $this->assertResponseOk();

        $this->assertNotEmpty($response['data']['token']);
    }
}
