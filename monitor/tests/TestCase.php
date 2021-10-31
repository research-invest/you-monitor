<?php

namespace Tests;

use App\Services\JsonRpc\JsonRpcClient;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = new JsonRpcClient();
    }
}
