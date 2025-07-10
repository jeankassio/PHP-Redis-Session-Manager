<?php

use PHPUnit\Framework\TestCase;
use JeanKassio\JsonRedisSessionHandler;

class JsonRedisSessionHandlerTest extends TestCase{
	
    private Redis $redis;
    private JsonRedisSessionHandler $handler;
    private string $sessionId = 'json-test-session';

    protected function setUp(): void{
        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1', 6379);

        $this->handler = new JsonRedisSessionHandler($this->redis);
    }

    public function testWriteAndRead(): void{
		
        $_SESSION = ['foo' => 'bar', 'baz' => 123];
        $this->handler->write($this->sessionId, '');

        $read = $this->handler->read($this->sessionId);
        $this->assertStringContainsString('foo|', $read);
        $this->assertStringContainsString('baz|', $read);
		
    }

    public function testDestroy(): void{
        $this->handler->write($this->sessionId, '');
        $this->assertTrue($this->handler->destroy($this->sessionId));

        $read = $this->handler->read($this->sessionId);
        $this->assertEquals('', $read);
    }
	
}
