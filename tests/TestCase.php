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

        /*
         * swapped $this->seed for Artisan so I could pass the fresh command into migrate
         * */
        Artisan::call('migrate:fresh --seed');
    }
}
