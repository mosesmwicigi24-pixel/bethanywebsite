<?php

declare(strict_types=1);

namespace Bethany\Services\Tests\Fake;

use Bethany\Services\Ai\AiClient;

/** Returns a canned reply and records the last prompt/options for assertions. */
final class FakeAiClient implements AiClient
{
    public ?string $lastPrompt = null;
    /** @var array<string,mixed> */
    public array $lastOptions = [];

    public function __construct(private string $reply)
    {
    }

    public function complete(string $prompt, array $options = []): string
    {
        $this->lastPrompt = $prompt;
        $this->lastOptions = $options;
        return $this->reply;
    }
}
