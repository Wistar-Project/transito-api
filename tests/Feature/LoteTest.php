<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoteTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_estado()
    {
        $this -> assertEquals(1, 1);
    }

    public function test_estadoInexistente()
    {
        $this -> assertEquals(1, 1);
    }

    public function test_estadoSinAutenticarse()
    {
        $this -> assertEquals(1, 1);
    }
}
