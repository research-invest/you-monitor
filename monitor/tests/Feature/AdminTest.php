<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminTest extends TestCase
{

    /**
     * @return void
     */
    public function test_StatisticsSuccess()
    {
        $statResponse = $this->client->send('getStatistic', [
            'page' => request('page', 1),
            'per_page' => 5,
        ]);

        $this->assertArrayHasKey('id', $statResponse);
        $this->assertArrayHasKey('jsonrpc', $statResponse);
        $this->assertEquals('2.0', $statResponse['jsonrpc']);
        $this->assertIsArray($statResponse['result']);

        $this->assertArrayHasKey('rows', $statResponse['result']);
        $this->assertArrayHasKey('total', $statResponse['result']);
        $this->assertArrayHasKey('perPage', $statResponse['result']);
        $this->assertArrayHasKey('page', $statResponse['result']);

        $this->assertArrayHasKey('url', $statResponse['result']['rows'][0]);
        $this->assertArrayHasKey('count', $statResponse['result']['rows'][0]);
        $this->assertArrayHasKey('created_at', $statResponse['result']['rows'][0]);

        $this->assertCount(3, $statResponse);
        $this->assertCount(4, $statResponse['result']);
    }
}
