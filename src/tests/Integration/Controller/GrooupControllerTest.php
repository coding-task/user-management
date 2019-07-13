<?php

namespace Tests\Integration\Controller;

use Tests\IntegrationTestCase;

class GroupControllerTest extends IntegrationTestCase
{
    /**
     * @test
     * @covers ::index
     */
    public function it_returns_all_groups()
    {
        $group = $this->get('/group', ['token' => $this->getAdminUser()]);

        $response = json_decode($group->response->getContent(), true);

        $this->assertResponseOk();

        $this->assertEquals(11, count($response['data']));
    }
    /**
     * @test
     * @covers ::create
     */
    public function it_creates_group()
    {
        $data = [
            'name' => 'test_group',
        ];

        $this->post('/group/create', $data,  ['token' => $this->getAdminUser()]);

        $this->assertResponseStatus(201);
        $this->seeInDatabase('groups', $data);
    }

    /**
     * @test
     * @covers ::create
     */
    public function it_throws_validation_exception_for_invalid_create_request()
    {
        $data = [];

        $group = $this->post('/group/create', $data,  ['token' => $this->getAdminUser()]);

        $response = json_decode($group->response->getContent(), true);

        $this->assertResponseStatus(422);

        $this->assertEquals([
            'errors' => [
                'name' => [
                    'The name field is required.',
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
        $group = factory('App\Group')->create();

        $data = [];

        $group = $this->put('/group/edit/'. $group->id, $data, ['token' => $this->getAdminUser()]);

        $response = json_decode($group->response->getContent(), true);

        $this->assertResponseStatus(422);

        $this->assertEquals([
            'errors' => [
                'name' => [
                    'The name field is required.',
                ],
            ],
        ], $response);

    }

    /**
     * @test
     * @covers ::update
     */
    public function it_throws_validation_exception_on_super_admin_update()
    {
        $data = [
            'name' => 'updated super admin role',
        ];

        $group = $this->put('group/edit/1', $data, ['token' => $this->getAdminUser()]);

        $response = json_decode($group->response->getContent(), true);

        $this->assertResponseStatus(422);

        $this->assertEquals([
            'errors' => [
                'app_error' => [
                    'Cannot Update Super Admin.',
                ],
            ],
        ], $response);
    }

    /**
     * @test
     * @covers ::update
     */
    public function it_update_role()
    {
        $group = factory('App\Group')->create();

        $data = [
            'name' => 'Test group update',
        ];

        $this->put('/group/edit/'. $group->id, $data, ['token' => $this->getAdminUser()]);

        $this->assertResponseStatus(204);
        $this->seeInDatabase('groups', $data);
    }

    /**
     * @test
     * @covers ::show
     */
    public function it_test_show_user()
    {
        $group = factory('App\Group')->create();

        $this->get('/group/show/'. $group->id, ['token' => $this->getAdminUser()]);

        $this->assertResponseOk();
    }

    /**
     * @test
     * @covers ::delete
     */
    public function it_throw_validation_exception_on_delete_group_having_users()
    {
        $group = $this->delete('group/delete/1', [], ['token' => $this->getAdminUser()]);

        $response = json_decode($group->response->getContent(), true);

        $this->assertResponseStatus(422);

        $this->assertEquals([
            'errors' => [
                'app_error' => [
                    'Cannot Delete Group. Group has Users.',
                ],
            ],
        ], $response);
    }

    /**
     * @test
     * @covers ::delete
     */
    public function it_delete_group()
    {
        $group = factory('App\Group')->create();

        $this->delete('group/delete/' . $group->id, [], ['token' => $this->getAdminUser()]);

        $this->assertResponseStatus(204);
        $this->notSeeInDatabase('users', $group->toArray());
    }
}
