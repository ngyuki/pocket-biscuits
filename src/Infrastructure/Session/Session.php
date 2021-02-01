<?php
namespace App\Infrastructure\Session;

class Session implements SessionInterface
{
    private function start()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function get(string $name)
    {
        $this->start();
        return $_SESSION[$name] ?? null;
    }

    public function set(string $name, $value)
    {
        $this->start();
        $_SESSION[$name] = $value;
    }

    public function clear()
    {
        $this->start();
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
}
