<?php

declare(strict_types=1);

namespace Bethany\Services\Ai;

/**
 * The one HTTP operation the AI clients need. Kept as our own tiny interface (rather
 * than depending on Guzzle directly) so the AI clients are unit-testable with an
 * in-memory fake and carry no hard vendor dependency.
 */
interface HttpTransport
{
    /**
     * POST a JSON body and return the decoded JSON response as an associative array.
     *
     * @param array<string,string> $headers
     * @param array<string,mixed>  $body
     *
     * @return array<string,mixed>
     *
     * @throws AiException on network error or non-2xx response.
     */
    public function postJson(string $url, array $headers, array $body): array;
}
