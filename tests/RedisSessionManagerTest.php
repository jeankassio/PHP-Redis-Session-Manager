<?php

use PHPUnit\Framework\TestCase;
use JeanKassio\RedisSessionManager;

class RedisSessionManagerTest extends TestCase{
	
    private RedisSessionManager $manager;
    private string $sessionId = 'test-session';

    protected function setUp(): void{
        $this->manager = new RedisSessionManager('127.0.0.1', 6379);
        $this->manager->setSessionId($this->sessionId);
    }

    public function testRedisConnection(): void{
        $this->assertTrue($this->manager->checkRedis());
    }

    public function testSetAndGetSession(): void{
		
        $data = ['user' => 'jean', 'role' => 'admin'];
        $this->assertTrue($this->manager->setSession($data));

        $session = $this->manager->getSession();
        $this->assertIsArray($session);
        $this->assertEquals('jean', $session['user']);
        $this->assertEquals('admin', $session['role']);
		
    }

    public function testDeleteSession(): void{
		
        $this->assertTrue($this->manager->delSession());

        $session = $this->manager->getSession();
        $this->assertFalse($session);
		
    }
	
}
