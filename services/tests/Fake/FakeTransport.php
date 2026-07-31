<?php

declare(strict_types=1);

namespace Bethany\Services\Tests\Fake;

use Bethany\Services\Ai\HttpTransport;

/** In-memory HttpTransport: returns a preset decoded response, captures the request. */
final class FakeTransport implements HttpTransport
{
    public ?string $url = null;
    /** @var array<string,string> */
    public array $headers = [];
    /** @var array<string,mixed> */
    public array $body = [];

    /** @param array<string,mixed> $response */
    public function __construct(private array $response)
    {
    }

    public function postJson(string $url, array $headers, array $body): array
    {
        $this->url = $url;
        $this->headers = $headers;
        $this->body = $body;
        return $this->response;
    }
}
