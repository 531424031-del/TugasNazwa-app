<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * The home route sends guests to the login page.
     */
    public function test_root_redirects_guests_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    /**
     * The login page itself is reachable without a session.
     */
    public function test_login_page_is_reachable(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
    }
}
