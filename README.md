# PHP Redis Session Manager

A simple and flexible PHP library for managing sessions using Redis, compatible with both **standard HTTP pages** and **WebSocket servers**.

---

## Installation

You can install via Composer:

```bash
composer require jeankassio/php-redis-session-manager
```

## What is this?
PHP Redis Session Manager allows you to manage PHP sessions directly with Redis, supporting both:

- Traditional HTTP requests (automatic $_SESSION)

- WebSocket environments (manual control of session read/write/delete)

## Usage

### For Normal PHP Pages (Non-WebSocket)

```php

require_once(dirname(__FILE__) ."/vendor/autoload.php");

use JeanKassio\RedisSessionManager;

$redisSessionManager = new RedisSessionManager('127.0.0.1', 6379, 'Password'); // Password is optional

$redisSessionManager->start(); // Starts the session and sets Redis as handler

if (!$redisSessionManager->checkRedis()) {
    // Handle Redis connection failure
}

// Set session values
$_SESSION['anything'] = 'anything';

// Get session values
$anything = $_SESSION['anything'];

```

### For WebSocket Usage

**Do not call start() inside WebSocket servers!**
```php

require_once(dirname(__FILE__) ."/vendor/autoload.php");

use JeanKassio\RedisSessionManager;

$redisSessionManager = new RedisSessionManager('127.0.0.1', 6379, 'Password'); // Password is optional

// Get session ID from your WebSocket logic
$sessionId = 'your-session-id';
$redisSessionManager->setSessionId($sessionId);

// Get full session as array
$session = $redisSessionManager->getSession();

// Set session data
$newSession = [
    'anything' => 'anything',
    'data' => $data,
    'username' => 'jeankassio'
];

$success = $redisSessionManager->setSession($newSession);

// Delete user session completely
$redisSessionManager->delSession();

```

## Features
- Password-protected Redis connection (optional)
- Lightweight and compatible with PHP-FPM, CLI, and Swoole/WebSocket
- Stores session as JSON internally
- Supports merging existing session data automatically

## Important Notes
- Redis must be running and reachable via the host and port you provide.
- When using inside WebSocket servers, make sure you:
  - Extract and set the sessionId before using
  - Avoid using session_start() or $_SESSION directly


![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Redis](https://img.shields.io/badge/redis-compatible-green)
