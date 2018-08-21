<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExampleTest extends TestCase {
  use DatabaseMigrations;

  public function testBasicTest() {
    $response = $this->get('/');

    $response->assertStatus(200);
  }
}
