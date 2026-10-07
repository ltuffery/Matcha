<?php

namespace Controllers;

use Matcha\Api\Model\Preference;
use Tests\MatchaTestCase;

class LocalisationControllerTest extends MatchaTestCase
{
    private Preference $preferences;

    public function setUp(): void
    {
        parent::setUp();

        $this->preferences = Preference::factory()->create();
    }

    public function testWithNoData(): void
    {
        $response = $this->withHeader(
            ['Authorization' => 'Bearer ' . $this->preferences->user()->generateJWT()]
        )
            ->put('/users/me/localisation');

        $response->assertStatus(202);
    }

    public function testWithValidData(): void
    {
        $response = $this->withHeader(
            ['Authorization' => 'Bearer ' . $this->preferences->user()->generateJWT()]
        )
            ->put('/users/me/localisation', [
                'lat' => 1.555,
                'lon' => 2.555,
            ]);

        $response->assertStatus(204);
    }
}