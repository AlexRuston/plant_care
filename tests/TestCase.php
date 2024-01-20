<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    use RefreshDatabase;
    /**
     * Preparation for each test
     */
    public function setUp() : void
    {
        parent::setUp();

        // seed the database
        $this->seed();
    }
}
