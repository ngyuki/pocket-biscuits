<?php
namespace App\Infrastructure\Session;

interface SessionInterface
{
    public function get(string $name);
    public function set(string $name, $value);
    public function clear();
}
