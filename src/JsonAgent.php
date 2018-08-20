<?php

namespace Berle\JsonAgent;

use Berle\Logger\{ CallbackLogger, LoggerInterface };
use GuzzleHttp\Client;

class JsonAgent implements JsonAgentInterface
{
    
    protected $guzzle, $logger;
    
    public function __construct(
        Client          $guzzle,
        LoggerInterface $logger = null
    ) {
        $this->guzzle = $guzzle;
        $this->logger = $logger ?: new CallbackLogger();
    }
    
    public function logger(): LoggerInterface
    {
        return $this->logger;
    }
    
    public function get(string $url, array $query = []): array
    {
        return $this->request('GET', $url, $query);
    }
    
    public function post(string $url, array $query = [], array $body = []): array
    {
        return $this->request('POST', $url, $query, $body);
    }
    
    public function put(string $url, array $query = [], array $body = []): array
    {
        return $this->request('PUT', $url, $query, $body);
    }
    
    public function delete(string $url, array $query = []): array
    {
        return $this->request('DELETE', $url, $query);
    }
    
    protected function request(string $type, string $url, array $query, array $body = null): array
    {
        if (count($query) > 0) {
            $url .= '?' . http_build_query($query);
        }
        
        $this->logger->debug($type, [
            'url'   => $url,
            'query' => $query,
            'body'  => $body,
        ]);

        if (is_null($body)) {
            $response = $this->guzzle->request($type, $url);
        } else {
            $response = $this->guzzle->request($type, $url, [ 'json' => $body ]);
        }
        
        $json = $this->decode($response->getBody());

        $this->logger->debug("RESPONSE", $json);
        
        return $json;
    }
    
    protected function decode(string $json): array
    {
        return json_decode($json, true);
    }

}