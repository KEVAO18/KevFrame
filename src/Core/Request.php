<?php

namespace App\Core;

class Request{

    private array $params = [];

    public function __construct(array $params = [])
    {
        $this->params = $params;
    }

    public function get(string $key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }

    public function all(): array
    {
        return $this->params;
    }

    public function set(string $key, $value): void
    {
        $this->params[$key] = $value;
    }
}
