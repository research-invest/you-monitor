<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewedPageTest extends TestCase
{

    /**
     * @return void
     */
    public function test_viewPageSuccess()
    {
        $data = $this->client->send('viewPage', [
            'url' => request()->url(),
            'date' => time(),
        ]);

        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('jsonrpc', $data);
        $this->assertEquals('2.0', $data['jsonrpc']);
        $this->assertIsArray($data['result']);
        $this->assertArrayHasKey('id', $data['result']);
        $this->assertCount(3, $data);
    }

    /**
     * @return void
     */
    public function test_viewPageErrorDate()
    {
        $data = $this->client->send('viewPage', [
            'url' => request()->url(),
        ]);

        $error = [
                'date' => ['The date field is required.']
        ];

        $this->assertEquals($error, $data['result']['error']);
    }

    /**
     * @return void
     */
    public function test_viewPageErrorUrl()
    {
        $data = $this->client->send('viewPage', [
            'date' => time(),
        ]);

        $error = [
                'url' => ['The url field is required.']
        ];

        $this->assertEquals($error, $data['result']['error']);
    }
}
