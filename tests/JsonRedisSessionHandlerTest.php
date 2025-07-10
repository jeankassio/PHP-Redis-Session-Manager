<?php

use PHPUnit\Framework\TestCase;
use JeanKassio\JsonRedisSessionHandler;

class JsonRedisSessionHandlerTest extends TestCase
{
    private $redis;
    private $handler;

    protected function setUp(): void
    {
        $host = getenv('REDIS_HOST') ?: '127.0.0.1';
        $port = getenv('REDIS_PORT') ?: 6379;
        $pass = getenv('REDIS_PASS') ?: null;

        $this->redis = new Redis();
        $this->redis->connect($host, $port);

        if (!is_null($pass)) {
            $this->redis->auth($pass);
        }

        $this->handler = new JsonRedisSessionHandler($this->redis);
    }

    public function testWriteAndRead()
    {
        $_SESSION = ['test_key' => 'test_value'];

        $this->handler->write('phpunit_test', '');
        $session = $this->handler->read('phpunit_test');

        $this->assertIsString($session);
        $this->assertStringContainsString('test_key', $session);
    }

    public function testDestroy()
    {
        $_SESSION = ['remove_key' => 'remove_value'];
        $this->handler->write('phpunit_test_remove', '');

        $deleted = $this->handler->destroy('phpunit_test_remove');
        $this->assertTrue($deleted);
    }
}
