<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;

class IntegrationTestCase extends TestCase
{
    use DatabaseMigrations;

    /**
     * Set up.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->runDatabaseMigrations();

        $this->artisan('db:seed');
    }

    /**
     * Tear Down.
     */
    public function tearDown(): void
    {
        $this->beforeApplicationDestroyed(function () {
            app('db')->connection()->disconnect();
        });

        parent::tearDown();
    }

    /**
     * Get admin user.
     *
     * @return string|null
     */
    public function getAdminUser()
    {
        $admin = $this->post($this->baseUrl . '/user/authenticate', [
            'email' => 'superadmin@admin.com',
            'password' => 'password@123',
        ]);

        $response = json_decode($admin->response->getContent(), true);

        return $response['data']['token'] ?? null;

    }
}
