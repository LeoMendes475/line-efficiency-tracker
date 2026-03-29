<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        try {
            $response = $this->get('/');
            $response->assertStatus(200);
        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getTraceAsString());
        }
    }
}
