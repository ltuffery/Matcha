<?php

use Matcha\Api\Testing\Cases\DatabaseTestCase;
use Matcha\Api\Testing\Cases\HttpTestCase;
use PHPUnit\Framework\TestCase;

class ChatControllerTest extends TestCase
{
    use HttpTestCase;
    use DatabaseTestCase;

    public function setUp(): void
    {
        $this->setUpDatabase();

        
    }
}