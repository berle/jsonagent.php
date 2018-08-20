<?php

namespace Berle\JsonAgent;

interface JsonAgentInterface
{

    public function logger(): LoggerInterface;
    public function get(string $url, array $query = []): array;
    public function put(string $url, array $query = [], array $body = []): array;
    public function post(string $url, array $query = [], array $body = []): array;
    public function delete(string $url, array $query = []): array;
    
}
