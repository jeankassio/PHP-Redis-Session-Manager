<?php
use JeanKassio\RedisSessionManager;
use PHPUnit\Framework\TestCase;

class RedisSessionManagerTest extends TestCase
{
    private $host;
    private $port;
    private $pass;

    protected function setUp(): void
    {
        $this->host = getenv('REDIS_HOST') ?: '127.0.0.1';
        $this->port = getenv('REDIS_PORT') ?: 6379;
        $this->pass = getenv('REDIS_PASS') ?: null;
    }

    public function testRedisConnection()
    {
        $manager = new RedisSessionManager($this->host, $this->port, $this->pass);
        $this->assertTrue($manager->checkRedis());
    }

    public function testSetAndGetSession()
    {
        $manager = new RedisSessionManager($this->host, $this->port, $this->pass);
        $manager->setSessionId('phpunit_test');

        $manager->setSession(['foo' => 'bar']);
        $session = $manager->getSession();

        $this->assertIsArray($session);
        $this->assertEquals('bar', $session['foo']);
    }

    public function testDeleteSession()
    {
        $manager = new RedisSessionManager($this->host, $this->port, $this->pass);
        $manager->setSessionId('phpunit_test');

        $manager->setSession(['foo' => 'bar']);
        $this->assertTrue($manager->delSession());

        $this->assertFalse($manager->getSession());
    }
}
