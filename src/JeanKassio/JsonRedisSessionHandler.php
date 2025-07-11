<?php

namespace JeanKassio;

use SessionHandlerInterface;

class JsonRedisSessionHandler implements SessionHandlerInterface {
    private $redis;
	private $prefix;

    public function __construct($_redis, ?string $_prefix = ''){
        $this->redis = $_redis;
		$this->prefix = $_prefix;
    }

    public function open(string $savePath, string $sessionName): bool {
        return true;
    }

    public function close(): bool {
        return true;
    }

    public function read(string $id): string|false {
        $json = $this->redis->get($this->prefix . $id);
        if($json === false){
			return '';
		}
        $data = json_decode($json, true);
        if(!is_array($data)){
			return '';
		}
        $serialized = '';
        foreach($data as $key => $value){
            $serialized .= $key . '|' . serialize($value);
        }
        return $serialized;
    }

    public function write(string $id, string $data): bool {
		
        $json = json_encode($_SESSION);
        $ttl = (int)ini_get("session.gc_maxlifetime");
        return $this->redis->setex($this->prefix . $id, $ttl, $json);
		
    }

    public function destroy(string $id): bool {
        return (bool)$this->redis->del($this->prefix . $id);
    }

    public function gc(int $max_lifetime): int|false {
        return 0;
    }
	
}