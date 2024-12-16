<?php

use Matcha\Api\Model\User;
use Matcha\Api\Testing\Cases\DatabaseTestCase;
use Matcha\Api\Testing\Cases\HttpTestCase;
use PHPUnit\Framework\TestCase;

class SearchProfileControllerTest extends TestCase
{
    use HttpTestCase;
    use DatabaseTestCase;

    public function setUp(): void
    {
        $this->setUpDatabase();
    }

    public function tearDown(): void
    {
        Flight::response()->clear();
    }

    public function testEmptyQuery(): void
    {
        // TODO: Test....
    }
}