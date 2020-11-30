<?php

namespace Seungmun\Sens\Tests;

use Mockery as m;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class SensSmsChannelTest extends TestCase
{
    /**
     * @var \Mockery\MockInterface|\GuzzleHttp\Client
     */
    private $guzzleHttp;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->guzzleHttp = m::mock(Client::class);
    }

    /**
     * @return void
     */
    public function testIamSorryBecauseTestsAreSoNuisance()
    {
        $this->assertTrue(true);
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        m::close();
    }
}
