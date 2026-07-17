<?php

declare(strict_types=1);

namespace Bethany\Services\Ai;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Production HttpTransport backed by Guzzle (already a project dependency).
 */
final class GuzzleTransport implements HttpTransport
{
    public function __construct(
        private ClientInterface $http,
        private float $timeoutSeconds = 30.0,
    ) {
    }

    public function postJson(string $url, array $headers, array $body): array
    {
        try {
            $response = $this->http->request('POST', $url, [
                'headers' => $headers,
                'json' => $body,
                'timeout' => $this->timeoutSeconds,
                'http_errors' => true,
            ]);
        } catch (GuzzleException $e) {
            throw new AiException('AI HTTP request failed: ' . $e->getMessage(), 0, $e);
        }

        $raw = (string) $response->getBody();
        $decoded = json_decode($raw, true);

        if (! is_array($decoded)) {
            throw new AiException('AI response was not valid JSON.');
        }

        return $decoded;
    }
}
