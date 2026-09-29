<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_root_redirects_to_the_react_app(): void
    {
        $this->get('/')->assertRedirect('/app');
    }
}
