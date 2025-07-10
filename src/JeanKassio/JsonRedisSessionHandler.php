<?php

namespace JeanKassio;

use SessionHandlerInterface;

class JsonRedisSessionHandler implements SessionHandlerInterface {
    private $redis;

    public function __construct($_redis){
        $this->redis = $_redis;
    }

    public function open(string $savePath, string $sessionName): bool {
        return true;
    }

    public function close(): bool {
        return true;
    }

    public function read(string $id): string|false {
        $json = $this->redis->get($id);
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
        return $this->redis->setex($id, $ttl, $json);
		
    }

    public function destroy(string $id): bool {
        return (bool)$this->redis->del($id);
    }

    public function gc(int $max_lifetime): int|false {
        return 0;
    }
	
}