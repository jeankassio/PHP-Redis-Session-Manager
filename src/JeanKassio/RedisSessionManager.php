<?php

namespace JeanKassio;

use JeanKassio\JsonRedisSessionHandler;
use Redis;

class RedisSessionManager{
	
	private $prefix;
	private $ttl;
	private $redis;
	private $handler;
	private $sessionId;
	
	public function __construct(string $host, int $port, ?$pass = null){
		
		$this->prefix = ini_get('session.save_path') ?: 'PHPREDIS_SESSION:';
		$this->ttl = (int)ini_get("session.gc_maxlifetime");
		
		$this->redis = new Redis();
		$this->redis->connect($host, $port);
		
		if(!is_null($pass)){
			$this->redis->auth($pass);
		}
		
		$this->handler = new JsonRedisSessionHandler($this->redis);
		
	}
	
	public function start(){
		
		session_start();
		
	}
	
	public function setSessionId(string $sessionId){
		
		$this->sessionId = $sessionId;
		
	}
	
	public function getSession(): array|false {
		
		if(($json = $this->redis->get($this->sessionId)) === false || !is_array($json)){
			return '';
		}
		
		return json_decode($json, true);
		
	}
	
	public function setSession(array $data): bool{
		
		if(is_array($session = $this->getSession())){
			$data = array_merge($session, $data);
		}
		
		$json = json_encode($data);
		
		return $this->redis->setex($this->sessionId, $this->ttl, $json);
		
	}
	
	public function delSession(): bool{
		
		return (bool)$this->redis->del($this->sessionId);
		
	}
	
}