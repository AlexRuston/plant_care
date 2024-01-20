<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
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
