<?php

namespace Tests\Integration\Controller;

use Tests\IntegrationTestCase;

class UserControllerTest extends IntegrationTestCase
{
    /**
     * @test
     * @covers ::index
     */
    public function it_returns_all_users()
    {
        $user = $this->get('/user', ['token' => $this->getAdminUser()]);

        $response = json_decode($user->response->getContent(), true);

        $this->assertResponseOk();

        $this->assertEquals(11, count($response['data']));
    }
    /**
     * @test
     */
    public function it_creates_user()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => 'password',
        ];

        $expected = [
            'name' => 'Test User',
            'email' => 'test@test.com',
        ];

        $this->post('/user/create', $data,  ['token' => $this->getAdminUser()]);

        $this->assertResponseStatus(201);
        $this->seeInDatabase('users', $expected);
    }

    /**
     * @test
     * @covers ::create
     */
    public function it_throws_validation_exception_for_invalid_create_request()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@test.com',
        ];

        $user = $this->post('/user/create', $data,  ['token' => $this->getAdminUser()]);

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
     * @covers ::update
     */
    public function it_throws_validation_exception_for_invalid_update_request()
    {
        $user = factory('App\User')->create();

        $data = [
            'name' => 'Test User',
        ];

        $user = $this->put('/user/edit/'. $user->id, $data, ['token' => $this->getAdminUser()]);

        $response = json_decode($user->response->getContent(), true);

        $this->assertResponseStatus(422);

        $this->assertEquals([
            'errors' => [
                'email' => [
                    'The email field is required.',
                ],
            ],
        ], $response);

    }


    /**
     * @test
     * @covers ::update
     */
    public function it_update_user()
    {
        $user = factory('App\User')->create();

        $data = [
            'name' => 'Test User Update',
            'email' => 'testupdate@gmail.com'
        ];

        $this->put('/user/edit/'. $user->id, $data, ['token' => $this->getAdminUser()]);

        $this->assertResponseStatus(204);
        $this->seeInDatabase('users', $data);
    }

    /**
     * @test
     * @covers ::show
     */
    public function it_test_show_user()
    {
        $user = factory('App\User')->create();

        $this->get('/user/show/'. $user->id, ['token' => $this->getAdminUser()]);

        $this->assertResponseOk();
    }

    /**
     * @test
     * @covers ::delete
     */
    public function it_throw_validation_exception_on_delete_super_admin()
    {
        $user = $this->delete('user/delete/1', [], ['token' => $this->getAdminUser()]);

        $response = json_decode($user->response->getContent(), true);

        $this->assertResponseStatus(422);

        $this->assertEquals([
            'errors' => [
                'app_error' => [
                    'Cannot delete Super Admin.',
                ],
            ],
        ], $response);
    }

    /**
     * @test
     * @covers ::delete
     */
    public function it_delete_user()
    {
        $user = factory('App\User')->create();

        $this->delete('user/delete/' . $user->id, [], ['token' => $this->getAdminUser()]);

        $this->assertResponseStatus(204);
        $this->notSeeInDatabase('users', $user->toArray());
    }

    /**
     * @test
     * @covers ::assignUserToGroup
     */
    public function it_throws_validation_exception_on_user_assign_to_group_request()
    {
        $data = [
            'user_id' => '3',
        ];

        $user = $this->post('/user/assign-to-group/', $data, ['token' => $this->getAdminUser()]);

        $response = json_decode($user->response->getContent(), true);

        $this->assertResponseStatus(422);

        $this->assertEquals([
            'errors' => [
                'group_id' => [
                    'The group id field is required.',
                ],
            ],
        ], $response);
    }

    /**
     * @test
     * @covers ::assignUserToGroup
     */
    public function it_assign_user_to_group()
    {
        $data = [
            'user_id' => '3',
            'group_id' => '2',
        ];

        $this->post('/user/assign-to-group/', $data, ['token' => $this->getAdminUser()]);

        $this->assertResponseStatus(204);
        $this->seeInDatabase('user_groups', $data);
    }

    /**
     * @test
     * @covers ::removeUserFromGroup
     */
    public function it_throws_validation_exception_on_user_remove_from_group_request()
    {
        $data = [
            'user_id' => '3',
        ];

        $user = $this->post('/user/remove-from-group/', $data, ['token' => $this->getAdminUser()]);

        $response = json_decode($user->response->getContent(), true);

        $this->assertResponseStatus(422);

        $this->assertEquals([
            'errors' => [
                'group_id' => [
                    'The group id field is required.',
                ],
            ],
        ], $response);
    }

    /**
     * @test
     * @covers ::removeUserFromGroup
     */
    public function it_throws_exception_on_remove_super_admin_from_group()
    {
        $data = [
            'user_id' => '1',
            'group_id' => '1',
        ];

        $user = $this->post('/user/remove-from-group/', $data, ['token' => $this->getAdminUser()]);

        $response = json_decode($user->response->getContent(), true);

        $this->assertResponseStatus(422);

        $this->assertEquals([
            'errors' => [
                'app_error' => [
                    'Cannot Remove Super Admin.',
                ],
            ],
        ], $response);
    }

    /**
     * @test
     * @covers ::assignUserToGroup
     */
    public function it_remove_user_from_group()
    {
        $data = [
            'user_id' => '3',
            'group_id' => '2',
        ];

        $this->post('/user/remove-from-group/', $data, ['token' => $this->getAdminUser()]);

        $this->assertResponseStatus(204);
        $this->notSeeInDatabase('user_groups', $data);
    }
}
