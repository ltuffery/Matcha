<?php

namespace Tests;

use Flight;
use Matcha\Api\Testing\Cases\DatabaseTestCase;
use Matcha\Api\Testing\Cases\HttpTestCase;
use PHPUnit\Framework\TestCase;

class MatchaTestCase extends TestCase
{
    use HttpTestCase;
    use DatabaseTestCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    protected function tearDown(): void
    {
        Flight::response()->clear();
        parent::tearDown();
    }
}